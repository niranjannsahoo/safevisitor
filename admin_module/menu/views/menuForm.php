<h2><?=$text_form?></h2>
<form method="post" action="<?=$action?>">
  <p>
    <label for="edit-menu-title">Title</label>
    <input type="text" name="title" id="edit-menu-title" value="<?php echo $title; ?>">
  </p>
  <p>
    <label for="edit-menu-url">URL</label>
    <input type="text" name="url" id="edit-menu-url" value="<?php echo $url; ?>">
  </p>
  <p>
    <label for="edit-menu-class">Class</label>
    <input type="text" name="class" id="edit-menu-class" value="<?php echo $class; ?>">
  </p>
</form>
