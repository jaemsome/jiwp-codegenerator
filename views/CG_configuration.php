<?php

// Note: $this = KMI_Code_Generator

$EMPinItems = array(
    '0' => 'Reserved; do not use',
    '1' => 'Emulator Pins on PGC3/PGD3',
    '2' => 'Emulator Pins on PGC2/PGD2',
    '3' => 'Emulator Pins on PGC1/PGD1'
);

$WDTPostscalerItems = array(
    '0' => '1:1', '1' => '1:2', '2' => '1:4', '3' => '1:8', '4' => '1:16',
    '5' => '1:32', '6' => '1:64', '7' => '1:128', '8' => '1:256', '9' => '1:512',
    '10' => '1:1024', '11' => '1:2048', '12' => '1:4096', '13' => '1:8192', '14' => '1:16384',
    '15' => '1:32768'
);

$FNOSCItems = array(
    '0' => 'Fast RC Oscillator',
    '1' => 'Fast RC Oscillator with postscaler and PLL module',
    '2' => 'Primary Oscillator (XT, HS, EC)',
    '3' => 'Primary Oscillator with PLL module (XTPLL, HSPLL, ECPLL)',
    '4' => 'Secondary Oscillator',
    '5' => 'Low-Power RC Oscillator',
    '6' => 'Reserved',
    '7' => 'Fast RC Oscillator with Postscaler'
);

$FCKSMItems = array(
    '0' => 'Clock switching is enabled, Fail-Safe Clock Monitor is enabled',
    '1' => 'Clock switching is enabled, Fail-Safe Clock Monitor is disabled',
    '2' => 'Clock switching and Fail-Safe Clock Monitor are disabled'
);

$POSCMDItems = array(
    '0' => 'EC Oscillator mode selected',
    '1' => 'XT Oscillator mode selected',
    '2' => 'HS Oscillator mode selected',
    '3' => 'Primary Oscillator disabled'
);

?>
<h1>CONFIGURATION WORD 1</h1>
<p><label class="bold"><input type="checkbox" name="configuration[JTAG]" value="yes" <?php $this->_CheckedOrNot('configuration', 'JTAG'); ?> />JTAG Enable</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[debug]" value="yes" <?php $this->_CheckedOrNot('configuration', 'debug'); ?> />Enable Debug</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[watchDog]" value="yes" <?php $this->_CheckedOrNot('configuration', 'watchDog'); ?> />Watchdog Enable</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[watchWin]" value="yes" <?php $this->_CheckedOrNot('configuration', 'watchWin'); ?> />Windowed Watchdog Timer Disable bit</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[GCP]" value="yes" <?php $this->_CheckedOrNot('configuration', 'GCP'); ?> />General Segment Program Memory Code Protection bit</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[GWRP]" value="yes" <?php $this->_CheckedOrNot('configuration', 'GWRP'); ?> />General Segment Code Flash Write Protection bit</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[WDTPrescale]" value="yes" <?php $this->_CheckedOrNot('configuration', 'WDTPrescale'); ?> />WDT Prescaler Ratio (Checked = 1:128, unchecked = 1:32)</label></p>
<p>
    <label class="bold">Emulator Pin Placement:</label>
    <select name="configuration[EMPin]">
        <?php foreach($EMPinItems as $value => $key): ?>
            <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('configuration', 'EMPin', $value, 'selected'); ?>>
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p>
    <label class="bold">Watchdog Postscaler:</label>
    <select name="configuration[WDTPostscaler]">
        <?php foreach($WDTPostscalerItems as $value => $key): ?>
            <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('configuration', 'WDTPostscaler', $value, 'selected'); ?>>
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<h1>Configuration Word 2</h1>
<p><label class="bold"><input type="checkbox" name="configuration[IESO]" value="yes" <?php $this->_CheckedOrNot('configuration', 'IESO'); ?> />Internal External Switchover bit</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[IOL1WAY]" value="yes" <?php $this->_CheckedOrNot('configuration', 'IOL1WAY'); ?> />IOLOCK One-Way Set Enable bit</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[I2C1SEL]" value="yes" <?php $this->_CheckedOrNot('configuration', 'I2C1SEL'); ?> />I2C select (checked use defaults)</label></p>
<p><label class="bold"><input type="checkbox" name="configuration[OSCIOFCN]" value="yes" <?php $this->_CheckedOrNot('configuration', 'OSCIOFCN'); ?> />OSCO Pin Confiuration (checked OSCO/CLKO are CLKO, unchecked port I/O)</label></p>
<p class="kmi-responsive">
    <label class="bold">Primary Oscillator Confiuration: </label>
    <select name="configuration[POSCMD]">
        <?php foreach($POSCMDItems as $value => $key): ?>
            <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('configuration', 'POSCMD', $value, 'selected'); ?>>
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="kmi-responsive">
    <label class="bold">Oscillator Select bits: </label>
    <select name="configuration[FNOSC]">
        <?php foreach($FNOSCItems as $value => $key): ?>
            <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('configuration', 'FNOSC', $value, 'selected'); ?>>
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="kmi-responsive">
    <label class="bold">Clock Switching and Fail-Safe: </label>
    <select name="configuration[FCKSM]">
        <?php foreach($FCKSMItems as $value => $key): ?>
            <option value="<?php echo $value; ?>" <?php $this->_SelectedOrNot('configuration', 'FCKSM', $value, 'selected'); ?>>
                <?php echo $key; ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>
<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" id="btn_generate_configuration_code" name="generate_configuration_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>
