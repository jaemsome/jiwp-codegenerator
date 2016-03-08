<!-- $this = KMI_CodeGenerator -->
<ul class="grid columns-2">
    <li>
        <?php $this->_model_type = '2'; ?>
        <h3>Timer 2</h3>
        <p>
            <label class="bold">PWM Period in microseconds :</label>
            <input type="text" class="block kmi-one-column" name="pwm_timer<?php echo $this->_model_type; ?>[pwm_period]" value="<?php $this->_CurrentValue('pwm_timer'.$this->_model_type, 'pwm_period'); ?>" />
        </p>
        <p>
            <label class="bold">PR Value :</label>
            <input type="text" class="block kmi-one-column" name="pwm_timer<?php echo $this->_model_type; ?>[pr_value]" value="<?php $this->_CurrentValue('pwm_timer'.$this->_model_type, 'pr_value'); ?>" />
        </p>
        <p>
            <label class="bold">Timer Prescale :</label>
            <select class="" name="pwm_timer<?php echo $this->_model_type; ?>[timer_prescale]">
                <?php foreach(array('1', '8', '64', '256') as $key => $value): ?>
                    <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('pwm_timer'.$this->_model_type, 'timer_prescale', $value, 'selected'); ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="submit" class="kmi-one-column" id="btn_generate_pwm_timer<?php echo $this->_model_type; ?>_code" name="generate_pwm_timer<?php echo $this->_model_type; ?>_code" value="Generate Code" />
        </p>
    </li>
    <li>
        <?php $this->_model_type = '3'; ?>
        <h3>Timer 3</h3>
        <p>
            <label class="bold">PWM Period in microseconds :</label>
            <input type="text" class="block kmi-one-column" name="pwm_timer<?php echo $this->_model_type; ?>[pwm_period]" value="<?php $this->_CurrentValue('pwm_timer'.$this->_model_type, 'pwm_period'); ?>" />
        </p>
        <p>
            <label class="bold">PR Value :</label>
            <input type="text" class="block kmi-one-column" name="pwm_timer<?php echo $this->_model_type; ?>[pr_value]" value="<?php $this->_CurrentValue('pwm_timer'.$this->_model_type, 'pr_value'); ?>" />
        </p>
        <p>
            <label class="bold">Timer Prescale :</label>
            <select class="" name="pwm_timer<?php echo $this->_model_type; ?>[timer_prescale]">
                <?php foreach(array('1', '8', '64', '256') as $key => $value): ?>
                    <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('pwm_timer'.$this->_model_type, 'timer_prescale', $value, 'selected'); ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="submit" class="kmi-one-column" id="btn_generate_pwm_timer<?php echo $this->_model_type; ?>_code" name="generate_pwm_timer<?php echo $this->_model_type; ?>_code" value="Generate Code" />
        </p>
    </li>
</ul>

<ul class="grid columns-3">
    <?php $this->_model_type = 1; foreach(array('oc1', 'oc2', 'oc3', 'oc4', 'oc5') as $oc): ?>
        <li  class="align-center">
            <h3><?php echo strtoupper($oc); ?></h3>
            <p>
                <label class="bold inline-block">
                    <input type="radio" name="pwm_oc<?php echo $this->_model_type; ?>[timer]" <?php $this->_SelectedOrNot('pwm_oc'.$this->_model_type, 'timer', '2', 'checked'); ?> value="2" /> Timer 2
                </label>
                <label class="bold inline-block">
                    <input type="radio" name="pwm_oc<?php echo $this->_model_type; ?>[timer]" <?php $this->_SelectedOrNot('pwm_oc'.$this->_model_type, 'timer', '3', 'checked'); ?> value="3" /> Timer 3
                </label>
            </p>
            <p>
                <label class="bold inline-block">
                    <input type="radio" name="pwm_oc<?php echo $this->_model_type; ?>[single_continuous]" <?php $this->_SelectedOrNot('pwm_oc'.$this->_model_type, 'single_continuous', '1', 'checked'); ?> value="1" /> Single
                </label>
                <label class="bold inline-block">
                    <input type="radio" name="pwm_oc<?php echo $this->_model_type; ?>[single_continuous]" <?php $this->_SelectedOrNot('pwm_oc'.$this->_model_type, 'single_continuous', '2', 'checked'); ?> value="2" /> Continuous
                </label>
            </p>
            <p>
                <input type="submit" class="kmi-one-column" id="btn_generate_pwm_<?php echo strtolower($oc); ?>_code" name="generate_pwm_<?php echo strtolower($oc); ?>_code" value="Generate Code" />
            </p>
        </li>
    <?php $this->_model_type++; endforeach; ?>
</ul>

<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>