// Simple vanilla JS for toggling sidebar or handling small UI interactions
document.addEventListener('DOMContentLoaded', function(){
  // future: toggle sidebar if needed
  var toggles = document.querySelectorAll('[data-toggle="sidebar"]');
  toggles.forEach(function(t){
    t.addEventListener('click', function(e){
      e.preventDefault();
      document.querySelector('.layout').classList.toggle('sidebar-hidden');
    });
  });
});
