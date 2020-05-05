// require([
// 	   'jquery', 'feather'
// 	], 
// 	function ($, feather) {
// 	    $(function () { // to ensure that code evaluates on page load
// 	    	feather.replace({ 'height':14, 'width':14 });
// 	    });
// });
// document.addEventListener("DOMContentLoaded", function() {
//   var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

//   if ("IntersectionObserver" in window) {
//     let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
//       entries.forEach(function(entry) {
//         if (entry.isIntersecting) {
//           let lazyImage = entry.target;
//           lazyImage.src = lazyImage.dataset.src;
//           lazyImage.srcset = lazyImage.dataset.srcset;
//           lazyImage.classList.remove("lazy");
//           lazyImageObserver.unobserve(lazyImage);
//         }
//       });
//     });

//     lazyImages.forEach(function(lazyImage) {
//       lazyImageObserver.observe(lazyImage);
//     });
//   } else {
//     // Possibly fall back to a more compatible method here
// 	    let lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
// 	  	let active = false;

// 	  const lazyLoad = function() {
// 	    if (active === false) {
// 	      active = true;

// 	      setTimeout(function() {
// 	        lazyImages.forEach(function(lazyImage) {
// 	          if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
// 	            lazyImage.src = lazyImage.dataset.src;
// 	            lazyImage.srcset = lazyImage.dataset.srcset;
// 	            lazyImage.classList.remove("lazy");

// 	            lazyImages = lazyImages.filter(function(image) {
// 	              return image !== lazyImage;
// 	            });

// 	            if (lazyImages.length === 0) {
// 	              document.removeEventListener("scroll", lazyLoad);
// 	              window.removeEventListener("resize", lazyLoad);
// 	              window.removeEventListener("orientationchange", lazyLoad);
// 	            }
// 	          }
// 	        });

// 	        active = false;
// 	      }, 50);
// 	    }
// 	  };

// 	  document.addEventListener("scroll", lazyLoad);
// 	  window.addEventListener("resize", lazyLoad);
// 	  window.addEventListener("orientationchange", lazyLoad);
//   }
// });

// if ('loading' in HTMLImageElement.prototype) {
//     const images = document.querySelectorAll('img[loading="lazy"]');
//     images.forEach(img => {
//       img.src = img.dataset.src;
//     });
//   } else {
//     // Dynamically import the LazySizes library
//     const script = document.createElement('script');
//     script.src =
//       'https://pagecdn.io/lib/lazysizes/5.1.2/lazysizes.min.js';
//     document.body.appendChild(script);
//   }
