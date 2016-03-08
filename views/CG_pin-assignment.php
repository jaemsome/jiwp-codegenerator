<!-- // NOTE: $this = KMI_CodeGenerator -->
<?php

$output_values_arr = array(
    'C1OUT', 'C2OUT', 'U1TX', 'U1RTS', 'U2TX', 'U2RTS', 'SD01', 'SCK1OUT', 'SS1OUT',
    'SD02', 'SCK2OUT', 'SS2OUT', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'OC1', 'OC2', 'OC3', 'OC4', 'OC5'
);

?>
<h1>INPUT</h1>
<ul class="grid columns-4">
    <?php foreach(CG_PinAssignment::$attributes['input'] as $field): ?>
        <li class="kmi-small-screen-fit">
            <label class="bold"><?php echo $field; ?>:</label>
            <select class="block" name="pin_assignment[<?php echo $field; ?>]">
                <option value="" selected="selected">----------</option>
                <?php for($i = 0; $i < 22; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php $this->_SelectedOrNot('pin_assignment', $field, $i, 'selected'); ?>>
                        RP<?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </li>
    <?php endforeach; ?>
</ul>

<p class="clear"></p>

<h1>OUTPUT</h1>
<ul class="grid columns-4">
    <?php foreach(CG_PinAssignment::$attributes['output'] as $field): ?>
        <li class="kmi-small-screen-fit">
            <label class="bold"><?php echo $field; ?>:</label>
            <select class="block" name="pin_assignment[<?php echo $field; ?>]">
                <option value="">----------------</option>
                <?php $counter = 0; foreach($output_values_arr as $value): ?>
                    <option value="<?php echo $counter; ?>" <?php $this->_SelectedOrNot('pin_assignment', $field, $counter, 'selected'); ?>>
                        <?php echo $value; ?>
                    </option>
                <?php $counter++; endforeach; ?>
            </select>
        </li>
    <?php endforeach; ?>
</ul>
<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" id="btn_generate_pin_assignment_code" name="generate_pin_assignment_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>