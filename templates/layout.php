<div class="wrap">

    <h2>
        
        <a href="<?php echo $this->getAdminPageUrl(); ?>">Fixed Navbar</a>        
        <a class="add-new-h2" href="<?php echo $this->getAdminPageUrl(array('view' => 'form')); ?>">Dodaj nowy slajd</a>
    </h2>
    
    <?php if($this->hasFlashMsg()): ?>
    <div id="message" class="<?php echo $this->getFlashMsgStatus(); ?>">
        <p><?php echo $this->getFlashMsg(); ?></p>
    </div>
    <?php endif; ?>
    
    <?php require_once $view; ?>
    
    <br style="clear:both;">
    
</div>