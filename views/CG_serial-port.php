<?php // NOTE: $this = KMI_CodeGenerator ?>
<p>
    <label class="bold inline-block">
        <input type="radio" name="serial_port<?php echo $this->__model_type; ?>[BRGH]" value="0" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'BRGH', '0', 'checked'); ?> /> BRGH = 0
    </label>
    <label class="bold inline-block">
        <input type="radio" name="serial_port<?php echo $this->__model_type; ?>[BRGH]" value="1" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'BRGH', '1', 'checked'); ?> /> BRGH = 1
    </label>
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="desiredBR<?php echo $this->__model_type; ?>">Desired Baud Rate :</label>
    <input type="text" class="kmi-four-columns" id="desiredBR<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[desiredBR]" value="<?php $this->_CurrentValue('serial_port'.$this->__model_type, 'desiredBR'); ?>" />
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="constantBR<?php echo $this->__model_type; ?>">Baud Rate Constant :</label>
    <input type="text" class="kmi-four-columns" id="constantBR<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[constantBR]" value="<?php $this->_CurrentValue('serial_port'.$this->__model_type, 'constantBR'); ?>" />
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns"></label>
    <input type="submit" class="kmi-four-columns bold btn-kmi-cg-calculate-baud-rate" id="serial_port<?php echo $this->__model_type; ?>_calculateBR" name="serial_port<?php echo $this->__model_type; ?>[calculateBR]" value="Calculate Baud Rate" />
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="dataBits<?php echo $this->__model_type; ?>">Data Bits 8 or 9:</label>
    <select id="dataBits<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[dataBits]">
        <?php foreach(array('8', '9') as $bit): ?>
            <option value="<?php echo $bit; ?>" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'dataBits', $bit, 'selected'); ?>>
                <?php echo $bit; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="parity<?php echo $this->__model_type; ?>">Parity:</label>
    <select id="parity<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[parity]">
        <?php foreach(array('No Parity', 'Even Parity', 'Odd Parity') as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'parity', $key, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="<?php echo $this->__model_type; ?>">Stop Bits:</label>
    <select id="<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[stopBits]">
        <?php foreach(array('1 Stop Bit', '2 Stop Bits') as $key => $value): ?>
            <option value="<?php echo $key+1; ?>" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'stopBits', $key+1, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="kmi-responsive">
    <label class="bold inline-block kmi-five-columns" for="flowControl<?php echo $this->__model_type; ?>">Flow Control:</label>
    <select id="flowControl<?php echo $this->__model_type; ?>" name="serial_port<?php echo $this->__model_type; ?>[flowControl]">
        <?php foreach(array('U1CTS, RTS and BCLK disabled', 'U1CTS and BCLK disabled', 'BCLK disabled', 'U1CTS disabled') as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php $this->_SelectedOrNot('serial_port'.$this->__model_type, 'flowControl', $key, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<ul class="grid columns-4">
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[polarity]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'polarity'); ?> /> Receive Polarity Inversion</label>
    </li>
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[loopBack]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'loopBack'); ?> /> Loop Back</label>
    </li>
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[autoBaud]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'autoBaud'); ?> /> Auto Baud</label>
    </li>
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[IREnable]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'IREnable'); ?> /> IR Enable</label>
    </li>
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[wake]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'wake'); ?> /> Wake Up on Start Bit</label>
    </li>
    <li>
        <label class="bold"><input type="checkbox" name="serial_port<?php echo $this->__model_type; ?>[RTSMode]" value="yes" <?php $this->_CheckedOrNot('serial_port'.$this->__model_type, 'RTSMode'); ?> /> RTS Mode checked = Simplex Mode Else Flow Control mode</label>
    </li>
</ul>
<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" id="btn_generate_serial_port<?php echo $this->__model_type; ?>_code" name="generate_serial_port<?php echo $this->__model_type; ?>_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>