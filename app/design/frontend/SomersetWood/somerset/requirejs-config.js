var config = {
    "map": {
        "*": {
            "lightslider": "js/lightslider",
            "magnific-popup": "js/jquery.magnific-popup",
            "slicknav": "js/jquery.slicknav",
	        "feather": "js/feather.min"
        },
    },
    deps: [
        "js/main" 
    ],
    shim: {
        'js/lightslider': {
            deps: ['jquery']
        }, 
        'js/jquery.magnific-popup': {
            deps: ['jquery']
        },
        'js/jquery.slicknav': {
            deps: ['jquery']
        }
    }
};