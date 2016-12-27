<?php 
if ($this->type == 'truefalseicon') {
    if ($this->response) {
        print '<i class="icon-ok green"> </i>';
        }    else {
        print '<i class="icon-cancel red"> </i>';    
        }
} elseif ($this->type == 'passthrough') {        
        if ($this->response) {
        print $this->response;
        }    else {
        print 'false';    
        }
        
        
} else {
    print _($this->response);
}
?>