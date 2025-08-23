<?php

namespace App\Controllers;

use App\Models\TblEmpsDataModel;
use App\Models\TblHolidaysDataModel;
use App\Models\TblStnSecsDataModel;
use App\Models\TblStnSubSecsDataModel;
use App\Libraries\myFuns;
use App\Libraries\DateUtils;
use Config\Services; // Added to ensure Services is imported

class Holidays extends BaseController
{
    protected $session;
    protected $empModel;
    protected $holidayModel;
    protected $secModel;
    protected $subSecModel;
    protected $myFuns;
    protected $dateUtils;
    protected $logger; // Add logger property

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->empModel = new TblEmpsDataModel();
        $this->holidayModel = new TblHolidaysDataModel();
        $this->secModel = new TblStnSecsDataModel();
        $this->subSecModel = new TblStnSubSecsDataModel();
        $this->myFuns = new myFuns();
        $this->dateUtils = new DateUtils();
        $this->logger = \Config\Services::logger(); // Initialize logger
        helper(['form', 'url']);
    }

    // Display list of holidays
    public function index()
    {
        $from = null;
        $to = null;
        $sortByDateOrder = null;
        $data['holidays'] = $this->holidayModel->getAll($from, $to, $sortByDateOrder);
        return view('holidays/index', $data);
    }

    public function create()
    {
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $name = $this->request->getPost('name');
        $descrp = $this->request->getPost('descrp');

        // *** Debugging: Log incoming POST data ***
        $this->logger->info('Holidays/create: Incoming POST Data: ' . json_encode($this->request->getPost()));

        if (!$start_date || !$end_date || !$name) {
            $this->logger->warning('Holidays/create: Missing required fields (start_date, end_date, name).');
            return redirect()->back()->with('error', 'Start Date, End Date, and Name are required fields.');
        }

        // *** Debugging: Capture validation result and errors ***
        $validationResult = $this->validateInputs($start_date, $end_date, $name, $descrp);

        if ($validationResult === false) {
            $errors = \Config\Services::validation()->getErrors();
            $errorMessage = 'Validation failed. Errors: ' . json_encode($errors);
            $this->logger->error('Holidays/create: ' . $errorMessage);
            return redirect()->back()->with('error', 'Validation failed. Please check your inputs. ' . implode(', ', $errors));
        }

        // Check for overlap with existing holidays
        if ($this->checkOverlap()) {
            $this->logger->warning('Holidays/create: New holiday overlaps with an existing holiday.');
            return redirect()->back()->with('error', 'The new holiday overlaps with an existing holiday.');
        }

        $data = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'name'       => $name,
            'descrp'     => $descrp,
        ];

        if ($this->holidayModel->insert($data)) {
            $this->logger->info('Holidays/create: Holiday created successfully: ' . json_encode($data));
            return redirect()->to('/holidays');
        } else {
            $this->logger->error('Holidays/create: Failed to create holiday. Database insert failed for data: ' . json_encode($data));
            return redirect()->back()->with('error', 'Failed to create holiday. Please try again.');
        }
    }

    public function checkOverlap($excludeHolidayId = null)
    {
        $holidays = $this->holidayModel->getAll();
        $newStartDate = $this->request->getPost('start_date');
        $newEndDate = $this->request->getPost('end_date');

        foreach ($holidays as $holiday) {
            // Skip the holiday being updated from the overlap check
            if ($excludeHolidayId !== null && $holiday->id == $excludeHolidayId) {
                continue;
            }

            $overlapDays = $this->dateUtils->countDaysInteract(
                $holiday->start_date,
                $holiday->end_date,
                $newStartDate,
                $newEndDate
            );

            // *** Debugging: Log overlap check details ***
            if ($overlapDays > 0) {
                $this->logger->info(
                    'Holidays/checkOverlap: Overlap detected. ' .
                    'Existing: [ID: ' . $holiday->id . ', Start: ' . $holiday->start_date . ', End: ' . $holiday->end_date . '] ' .
                    'New: [Start: ' . $newStartDate . ', End: ' . $newEndDate . '] ' .
                    'Overlap Days: ' . $overlapDays
                );
                return true;
            }
        }
        $this->logger->info('Holidays/checkOverlap: No overlap detected.');
        return false;
    }

    public function update()
    {
        $id = $this->request->getPost('holiday_id');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $name = $this->request->getPost('name');
        $descrp = $this->request->getPost('descrp');

        // *** Debugging: Log incoming POST data for update ***
        $this->logger->info('Holidays/update: Incoming POST Data: ' . json_encode($this->request->getPost()));


        if (!$id || !$start_date || !$end_date || !$name) {
            $this->logger->warning('Holidays/update: Missing required fields (holiday_id, start_date, end_date, name).');
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // *** Debugging: Capture validation result and errors for update ***
        $validationResult = $this->validateInputs($start_date, $end_date, $name, $descrp);
        if($validationResult === false) {
            $errors = \Config\Services::validation()->getErrors();
            $errorMessage = 'Validation failed during update. Errors: ' . json_encode($errors);
            $this->logger->error('Holidays/update: ' . $errorMessage);
            return redirect()->back()->with('error', 'Validation failed. Please check your inputs. ' . implode(', ', $errors));
        }

        // Check for overlap with existing holidays, excluding the current holiday being updated
        if ($this->checkOverlap($id)) {
            $this->logger->warning('Holidays/update: The updated holiday overlaps with an existing holiday (ID: ' . $id . ').');
            return redirect()->back()->with('error', 'The updated holiday overlaps with an existing holiday.');
        }

        $data = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'name'       => $name,
            'descrp'     => $descrp,
        ];

        if ($this->holidayModel->updateHoliday($id, $data)) {
            $this->logger->info('Holidays/update: Holiday updated successfully (ID: ' . $id . '): ' . json_encode($data));
            return redirect()->to('/holidays');
        } else {
            $this->logger->error('Holidays/update: Failed to update holiday (ID: ' . $id . '). Database update failed for data: ' . json_encode($data));
            return redirect()->back()->with('error', 'Failed to update holiday. Please try again.');
        }
    }

    public function delete()
    {
        $id = $this->request->getPost('holiday_id');
        $this->logger->info('Holidays/delete: Attempting to delete holiday with ID: ' . $id);

        if (!$id) {
            $this->logger->warning('Holidays/delete: Holiday ID is required for deletion.');
            return redirect()->back()->with('error', 'Holiday ID is required.');
        }

        if ($this->holidayModel->deleteHoliday($id)) {
            $this->logger->info('Holidays/delete: Holiday deleted successfully (ID: ' . $id . ').');
            return redirect()->to('/holidays');
        } else {
            $this->logger->error('Holidays/delete: Failed to delete holiday (ID: ' . $id . '). Database delete failed.');
            return redirect()->back()->with('error', 'Failed to delete holiday. Please try again.');
        }
    }

    protected function validateInputs($start_date, $end_date, $name, $descrp = null)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'start_date' => 'required|valid_date',
            'end_date'   => 'required|valid_date|date_greater_than_equal_to[start_date]',
            'name'       => 'required|min_length[3]',
            'descrp'     => 'permit_empty|max_length[255]',
        ]);

        $dataToValidate = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'name'       => $name,
            'descrp'     => $descrp,
        ];

        // *** Debugging: Log the data being passed to validation ***
        $this->logger->info('Holidays/validateInputs: Data to validate: ' . json_encode($dataToValidate));

        $isValid = $validation->run($dataToValidate);

        // *** Debugging: Log validation result and errors if any ***
        if (!$isValid) {
            $errors = $validation->getErrors();
            $this->logger->warning('Holidays/validateInputs: Validation failed. Errors: ' . json_encode($errors));
        } else {
            $this->logger->info('Holidays/validateInputs: Validation successful.');
        }

        return $isValid;
    }
}