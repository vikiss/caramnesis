<div class="container">    
  <div class="box">        
    <!-- echo out the system feedback (error and success messages) -->        
    <?php $this->renderFeedbackMessages(); ?>	
  </div>  
  <div class="clearfix">       
    <div class="md-col md-col-3 carcol">
      <?php  include 'carcol.php'; ?>       
    </div>
    <div class="md-col md-col-9 ">   
<?php
foreach ($this->chapters as $chapter) {
      
          ?> 
    <div class="mb1 mr1 p1 black bg-kclite left fauxfield square center  " >       
      <a href = "<?= Config::get('URL') . 'car/attribute_chapter/' . $car_id.'/'.$chapter; ?>">
        <?= _($chapter); ?></a>       
    </div>                                     
    <?php }  ?>
    </div>  
  </div>
</div>                                                         