const sideMenuWidth = "250px";

function openNav() {
    const sideMenu = document.getElementById("sideMenu");
    const contentWrapper = document.getElementById("content-wrapper");
    const topBar = document.querySelector(".top-bar");

    if (sideMenu) {
        sideMenu.style.width = sideMenuWidth;
    }
    if (contentWrapper) {
         contentWrapper.style.marginLeft = sideMenuWidth;
    }
     if (topBar) {
         topBar.style.marginLeft = sideMenuWidth;
     }
}

function closeNav() {
     const sideMenu = document.getElementById("sideMenu");
     const contentWrapper = document.getElementById("content-wrapper");
     const topBar = document.querySelector(".top-bar");

     if (sideMenu) {
        sideMenu.style.width = "0";
     }
     if (contentWrapper) {
         contentWrapper.style.marginLeft = "auto";
     }
      if (topBar) {
          topBar.style.marginLeft = "0";
      }

     const activeLis = document.querySelectorAll('.sidenav li.active');
     activeLis.forEach(activeLi => {
         activeLi.classList.remove('active');
         const activeSubMenu = activeLi.querySelector(':scope > .dropdown-menu, :scope > .sub-submenu');
         if (activeSubMenu) {
             activeSubMenu.style.maxHeight = '0px';
             activeSubMenu.style.overflow = 'hidden';
         }
         const toggle = activeLi.querySelector(':scope > .dropdown-toggle');
         if (toggle) {
            toggle.classList.remove('active');
         }
     });
}

document.addEventListener('DOMContentLoaded', () => {
    // Llama a openNav() para asegurar que el menú esté abierto al cargar
    openNav();

    const sideMenu = document.getElementById('sideMenu');

    if (!sideMenu) {
        console.error("Elemento #sideMenu no encontrado.");
        return;
    }

    sideMenu.addEventListener('click', function(event) {
        const toggle = event.target.closest('.dropdown-toggle');

        if (!toggle) return;

        event.preventDefault();

        const parentLi = toggle.parentElement;
        if (!parentLi) return;

        const subMenu = toggle.nextElementSibling;

        if (!subMenu || !(subMenu.classList.contains('dropdown-menu') || subMenu.classList.contains('sub-submenu'))) {
            console.warn("No se encontró submenú válido (UL/DIV con clase .dropdown-menu o .sub-submenu) para:", toggle);
            return;
        }

        const parentUl = parentLi.parentElement;
        if (parentUl) {
            const siblingLis = parentUl.querySelectorAll(':scope > li.active');
            siblingLis.forEach(activeLi => {

                if (activeLi !== parentLi) {
                    activeLi.classList.remove('active');
                    const activeSubMenu = activeLi.querySelector(':scope > .dropdown-menu, :scope > .sub-submenu');
                    if (activeSubMenu) {
                        activeSubMenu.style.maxHeight = '0px';
                        activeSubMenu.style.overflow = 'hidden';
                        updateParentHeights(activeSubMenu);
                    }
                    const siblingToggle = activeLi.querySelector(':scope > .dropdown-toggle');
                    if (siblingToggle) {
                        siblingToggle.classList.remove('active');
                    }
                }
            });
        }

        const isActive = parentLi.classList.contains('active');
        parentLi.classList.toggle('active', !isActive);
        toggle.classList.toggle('active', !isActive);

        if (!isActive) {
            subMenu.style.overflow = 'visible';

            subMenu.style.maxHeight = subMenu.scrollHeight + "px";

            subMenu.addEventListener('transitionend', function handleTransitionEnd(e) {
                if (e.propertyName === 'max-height' && subMenu.classList.contains('active')) {
                    if (parseFloat(subMenu.style.maxHeight) > 0) {
                        subMenu.style.overflow = 'hidden';
                    }
                }
            }, { once: true });

        } else {
            subMenu.style.maxHeight = '0px';
            subMenu.style.overflow = 'hidden';
        }

        updateParentHeights(subMenu);

    });

    function updateParentHeights(element) {
        let parentContainer = element.closest('.dropdown-menu, .sub-submenu');

        while (parentContainer) {
            const parentLi = parentContainer.parentElement;

            if (parentLi && parentLi.classList.contains('active')) {
                if (document.body.contains(parentContainer)) {
                    parentContainer.style.maxHeight = parentContainer.scrollHeight + "px";
                }

                parentContainer = parentLi.closest('.dropdown-menu, .sub-submenu');
            } else {
                break;
            }
        }
    }

const managerBtn = document.getElementById('managerBtn');
const managerDropdown = document.getElementById('managerDropdown');

if (managerBtn && managerDropdown) {
    managerBtn.addEventListener('click', function(event) {
        managerDropdown.classList.toggle('show');

        event.stopPropagation();
    });
}

window.addEventListener('click', function(event) {
    const currentManagerBtn = document.getElementById('managerBtn');
    const currentManagerDropdown = document.getElementById('managerDropdown');

    if (currentManagerDropdown && currentManagerDropdown.classList.contains('show')) {
        if (currentManagerBtn && !currentManagerBtn.contains(event.target) && !currentManagerDropdown.contains(event.target)) {
            currentManagerDropdown.classList.remove('show');
        }
    }

});




    });   
