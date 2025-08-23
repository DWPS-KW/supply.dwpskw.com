<option value="">غير محدد</option>
    <?php
        foreach($sub_secs as $sub_sec){
    ?>
            <option value="<?php echo $sub_sec->id; ?>">
                <?php echo $sub_sec->ar_name; ?> 
            </option>
    <?php
        }
    ?>

