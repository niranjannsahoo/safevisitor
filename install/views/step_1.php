<h1 id="license">Step 1 - License</h1>
<?php echo validation_errors('<p class="error">', '</p>'); ?>
<div class="box">
    <div class="scrollbox">
        <h2>MIT License</h2>

        <p>Copyright (C) 2018 Diggity CMS</p>

        <p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>

        <p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>

        <p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
    </div>
</div>
<div class="align_right">
    <?php echo form_open(); ?>
        <div class="box">
            <label>I agree to the license <?php echo form_checkbox(array('name' => 'accept', 'value' => '1')); ?></label>
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="Continue" />
    <?php echo form_close(); ?>
</div>