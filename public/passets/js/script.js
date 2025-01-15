// function toggelSubMenu(button){

//     if(!button.nextElementSibling.classList.contains('show')){

//         closeAllSubMenus()
//     }

//     button.nextElementSibling.classList.toggle('show');
//     button.classList.toggle('rotate');

//     if(sidebar.classList.contains('close')){
//         sidebar.classList.toggle('close')
//         toggleButton.classList.toggle('rotate')
//     }

// }

// // sidebar toggel function 

// const toggleButton = document.getElementById('toggle-btn');
// const sidebar = document.getElementById('sidebar');

// function toggleSidebar(){
//     sidebar.classList.toggle('close')
//     toggleButton.classList.toggle('rotate')

//     closeAllSubMenus()
  
// }


// // submenu or bottombar code in mobile devices 

// function closeAllSubMenus(){
//     Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
//         ul.classList.remove('show')
//         ul.previousElementSibling.classList.remove('rotate')
//     }) 
// }

// // Get all navigation links
// document.querySelectorAll('nav a').forEach(link => {
//     link.addEventListener('click', (e) => {
//         const line = document.getElementById('line');
        
//         // Trigger the animation
//         line.classList.add('line-active');

//         // Remove the animation class after it completes
//         setTimeout(() => {
//             line.classList.remove('line-active');
//         }, 500); // Match the transition duration in CSS
//     });
// });

// another for submenu 

document.addEventListener("DOMContentLoaded", () => {
  // Automatically open any submenu that has links inside
  document.querySelectorAll('.sub-menu').forEach(subMenu => {
      if (subMenu.querySelector('a')) {
          subMenu.classList.add('show');
          subMenu.previousElementSibling.classList.add('rotate');
      }
  });
});

// Existing functions
function toggelSubMenu(button) {
  if (!button.nextElementSibling.classList.contains('show')) {
      closeAllSubMenus();
  }

  button.nextElementSibling.classList.toggle('show');
  button.classList.toggle('rotate');

  if (sidebar.classList.contains('close')) {
      sidebar.classList.toggle('close');
      toggleButton.classList.toggle('rotate');
  }
}

// sidebar toggle function
const toggleButton = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');

function toggleSidebar() {
  sidebar.classList.toggle('close');
  toggleButton.classList.toggle('rotate');

  closeAllSubMenus();
}

// submenu or bottombar code in mobile devices
function closeAllSubMenus() {
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
      ul.classList.remove('show');
      ul.previousElementSibling.classList.remove('rotate');
  });
}

// Get all navigation links
document.querySelectorAll('nav a').forEach(link => {
  link.addEventListener('click', (e) => {
      const line = document.getElementById('line');
      
      // Trigger the animation
      line.classList.add('line-active');

      // Remove the animation class after it completes
      setTimeout(() => {
          line.classList.remove('line-active');
      }, 500); // Match the transition duration in CSS
  });
});


// Set current time and date
function updateTimeAndDate() {
  const now = new Date();

  // Format time with hours, minutes, and seconds
  const formattedTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  // Format date with month as a short name
  const formattedDate = now.toLocaleDateString('en-US', {
      day: '2-digit',
      month: 'short', // Short month name (e.g., Jan, Feb)
      year: 'numeric'
  });

  // Update the DOM
  document.getElementById('current-time').textContent = `${formattedDate} | ${formattedTime}`;
}

// Update time every second
setInterval(updateTimeAndDate, 1000);
updateTimeAndDate();



//light and dark mode toggle 

document.addEventListener("DOMContentLoaded", function () {
  const toggleSwitch = document.getElementById("mode-toggle");
  const body = document.body;
  const logo = document.getElementById("logo");

  // Retrieve logo paths from data attributes
  const lightModeLogo = logo.dataset.lightLogo;
  const darkModeLogo = logo.dataset.darkLogo;

  // Check local storage for saved mode preference
  const savedMode = localStorage.getItem("mode");
  if (savedMode) {
      body.classList.remove("light-mode", "dark-mode");
      body.classList.add(savedMode);
      logo.src = savedMode === "dark-mode" ? darkModeLogo : lightModeLogo;
      toggleSwitch.checked = savedMode === "dark-mode";
  }

  // Listen for toggle switch changes
  toggleSwitch.addEventListener("change", function () {
      if (toggleSwitch.checked) {
          body.classList.remove("light-mode");
          body.classList.add("dark-mode");
          localStorage.setItem("mode", "dark-mode");
          logo.src = darkModeLogo;
      } else {
          body.classList.remove("dark-mode");
          body.classList.add("light-mode");
          localStorage.setItem("mode", "light-mode");
          logo.src = lightModeLogo;
      }
  });
});


  
  





