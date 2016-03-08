<?php

// NOTE: $this = KMI_CodeGenerator

$port_values = array(
    'PORTA', 'PORTB', 'PORTC', 'PORTD', 'PORTE',
    'PORTF', 'PORTG', 'PORTH', 'PORTI', 'PORTJ'
);
    
$bit_values = array(
    'Bit 0', 'Bit 1', 'Bit 2', 'Bit 3',
    'Bit 4', 'Bit 5', 'Bit 6', 'Bit 7',
    'Bit 8', 'Bit 9', 'Bit 10', 'Bit 11',
    'Bit 12', 'Bit 13', 'Bit 14', 'Bit 15'
);

?>
<p class="align-center kmi-responsive">
    <label class="bold inline-block kmi-ten-columns">SCL:</label>
    <select class="kmi-six-columns" name="bit_banged_i2c[scl_port]">
        <?php foreach($port_values as $value): ?>
            <option <?php $this->_SelectedOrNot('bit_banged_i2c', 'scl_port', $value, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select class="kmi-six-columns" name="bit_banged_i2c[scl_bit]">
        <?php foreach($bit_values as $value): ?>
            <option <?php $this->_SelectedOrNot('bit_banged_i2c', 'scl_bit', $value, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="align-center kmi-responsive">
    <label class="bold inline-block kmi-ten-columns">SDA:</label>
    <select class="kmi-six-columns" name="bit_banged_i2c[sda_port]">
        <?php foreach($port_values as $value): ?>
            <option <?php $this->_SelectedOrNot('bit_banged_i2c', 'sda_port', $value, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select class="kmi-six-columns" name="bit_banged_i2c[sda_bit]">
        <?php foreach($bit_values as $value): ?>
            <option <?php $this->_SelectedOrNot('bit_banged_i2c', 'sda_bit', $value, 'selected'); ?>>
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="align-center kmi-responsive">
    <label class="bold inline-block kmi-ten-columns"></label>
    <input type="submit" class="kmi-four-columns" id="btn_generate_bit_banged_i2c_code" name="generate_bit_banged_i2c_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>