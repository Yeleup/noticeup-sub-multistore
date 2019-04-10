<?php if (count($stores) > 1) { ?>
<div class="pull-left">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store">
  <div class="btn-group">
    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
    <?php foreach ($stores as $store) { ?>
    <?php if ($store['store_id'] == $code) { ?>
        <span><?php echo $store['name']; ?></span> 
    <?php } ?>
    <?php } ?>
   <i class="fa fa-caret-down"></i></button>
    <ul class="dropdown-menu">
      <?php foreach ($stores as $store) { ?>
      <li>
	  <?php if ($store['store_id']) { ?>
	  <a class="btn btn-link btn-block" href="<?php echo $store['url']; ?>"><?php echo $store['name']; ?></a>
	  <?php } else { ?>
	  <button type="submit" class="btn btn-link btn-block"><?php echo $store['name']; ?></a> 
	  <?php } ?>
	  </li>
      <?php } ?>
    </ul>
  </div>
  <input type="hidden" name="code" value="0" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
</div>
<?php } ?>
