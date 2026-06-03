$(function() {

    let header = $("#header");
    let intro = $("#intro");
    let introH;
    let scrollPos = $(window).scrollTop();

    $(window).on("scroll load resize", function() {
        introH = intro.innerHeight();
        scrollPos = $(this).scrollTop();

        if( scrollPos > introH ) {
            header.addClass("fixed");
        } else {
            header.removeClass("fixed");
        }
    });

    
    
    /**/
    
    $("[data-scroll]").on("click", function(event) {
        event.preventDefault();
        
        let elementid = $(this).data('scroll');
        
        console.log(elemenid);
        
        let elementoffset = $(elementid).offset().top;
        
        $("html, body").animate({
            scrolltop: elementoffset - 70
        }, 700);
        
    });
});



// CARUSEL START team

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('#customSlider');
    const itemsContainer = slider.querySelector('.team__items');
    const items = slider.querySelectorAll('.team-member');
    const prevButton = slider.querySelector('.slider-control.prev');
    const nextButton = slider.querySelector('.slider-control.next');
  
    const itemsPerView = 4; // Количество видимых элементов
    const totalItems = items.length;
    const itemWidth = 100 / itemsPerView; // Ширина одного элемента в %
    let currentIndex = 0;
  
    // Update slider position
    const updateSlider = () => {
        const offset = -(currentIndex * itemWidth);
        itemsContainer.style.transform = `translateX(${offset}%)`;
    };
  
    // Handle "Next" button
    nextButton.addEventListener('click', () => {
        if (currentIndex < totalItems - itemsPerView) {
            currentIndex++;
            updateSlider();
        }
    });
  
    // Handle "Previous" button
    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });
  
    // Optional: Auto-slide every 4 seconds
    setInterval(() => {
        currentIndex = (currentIndex + 1) % (totalItems - itemsPerView + 1);
        updateSlider();
    }, 4000);
  });
  
  
  
  
  // CARUSEL END

