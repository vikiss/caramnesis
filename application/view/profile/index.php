<div class="container">
    <h1>ProfileController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>


        <div>
            
                <?php
                $user = $this->user;
                
                print $user->user_lang;
                
                  ?>
                  
                  <input type="text" name="startLat" id="startLat" />
                  <input type="text" name="startLon" id="startLon" />
                    
        </div>
    </div>
</div>
<script src="/js/geoloc.js"></script>
