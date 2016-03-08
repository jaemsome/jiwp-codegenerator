<?php // NOTE: $this = KMI_CodeGenerator ?>
<h1>CHECK FOR OUTPUT</h1>
<ul class="grid columns-4">
    <?php foreach(CG_PortB::$attributes as $attribute): ?>
        <?php if($attribute != 'id' && $attribute != 'project_id'): ?>
            <li>
                <label class="bold">
                    <input type="checkbox" name="portb[<?php echo $attribute; ?>]" value="yes" <?php $this->_CheckedOrNot('portb', $attribute); ?> /> <?php echo ucfirst(str_replace('_', ' ', $attribute)); ?>
                </label>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>