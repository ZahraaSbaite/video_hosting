document.addEventListener('click', function(e){
  // click to play/pause video thumbnails
  if(e.target.matches('.thumb')){
    try{
      if(e.target.paused) e.target.play(); else e.target.pause();
    }catch(err){}
  }
});
