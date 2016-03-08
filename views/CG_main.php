<!-- $this = KMI_CodeGenerator -->
<h1>PROGRAM COMPONENTS</h1>
<ul class="grid columns-3">
    <?php foreach(CG_Main::$attributes as $attribute): ?>
        <?php if($attribute != 'id' && $attribute != 'project_id'): ?>
            <li>
                <label class="bold">
                    <input type="checkbox" name="main[<?php echo $attribute; ?>]" <?php $this->_CheckedOrNot('main', $attribute); ?> value="yes" /> <?php echo ucwords(str_replace('_', ' ', $attribute)); ?>
                </label>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<p class="kmi-responsive">
    <label class="bold">Code before main:</label>
    <textarea name="main[before_main_includes]" rows="10" class="vertical-resize block kmi-one-column"><?php $this->_CurrentValue('main', 'before_main_includes'); ?></textarea>
</p>
<p class="kmi-responsive">
    <label class="bold">Code in main:</label>
    <textarea name="main[inside_main_includes]" rows="10" class="vertical-resize block kmi-one-column"><?php $this->_CurrentValue('main', 'inside_main_includes'); ?></textarea>
</p>
<p class="align-center kmi-responsive">
    <input type="submit" class="kmi-four-columns" id="btn_generate_main_code" name="generate_main_code" value="Generate Code" />
    <input type="submit" class="kmi-four-columns" name="save_project_code" value="Add Project" />
</p>