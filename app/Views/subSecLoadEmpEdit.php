<option value="" <?php if($sub_sec_id == null){echo 'selected';}?>>غير محدد</option>
    <?php
        foreach($sub_secs as $sub_sec){
    ?>
            <option value="<?php echo $sub_sec->id; ?>" <?php if($sub_sec->id == $sub_sec_id){echo 'selected';}?>>
                <?php echo $sub_sec->ar_name; ?> 
            </option>
    <?php
        }
    ?>

