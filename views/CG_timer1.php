<!-- $this = KMI_CodeGenerator -->
<p class="kmi-responsive">
    <label class="bold inline-block kmi-four-columns">Number of Interrupts per second :</label>
    <input type="text" class="kmi-four-columns" name="timer1[interrupt_number]" value="<?php $this->_CurrentValue('timer1', 'interrupt_number'); ?>" />
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-four-columns">Reload Value :</label>
    <input type="text" class="kmi-four-columns" name="timer1[reload_value]" value="<?php $this->_CurrentValue('timer1', 'reload_value'); ?>" />
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-four-columns"></label>
    <input type="submit" id="timer1_calculateReload" class="kmi-four-columns bold" name="timer1[calculateReload]" value="Calculate Reload" />
</p>
<p class="kmi-responsive">
    <label class="bold">Code to be entered in the Timer 1 interrupt routine.</label>
    <textarea name="timer1[t1_includes]" rows="10" class="vertical-resize block kmi-two-columns"><?php $this->_CurrentValue('timer1', 't1_includes'); ?></textarea>
    You have 255 characters left.
</p>
<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" id="btn_generate_timer1_code" name="generate_timer1_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>