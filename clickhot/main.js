function fadeAround(n)
{
	if(!n)
    {
       $(".guest").fadeOut(3000);
    	fadeAround(true);
    }else{
        $(".guest").fadeIn(1000);     	
    	fadeAround(false);    
    }
};


$(document).ready(function(){
   	// alert('cc');
    
   	// $(".guest").fadeIn(2000);
  	// $(".guest").animate({
   	//     fontSize:'+=0.5em'
  	//  },2000);

    $(".link").hover(
       
        function(){
            //鼠标进入
            
            $(this).css("background","rgba(100,100,100,0.2)");
    	},
        function(){
            //鼠标移出
            $(this).css("background","transparent");
        }
    );
    
	fadeAround(false);
});

    
