/*	-----------------------------------------------------------------------------------------------
	Namespace
--------------------------------------------------------------------------------------------------- */

var shiver = shiver || {},
    $ = jQuery;


/*	-----------------------------------------------------------------------------------------------
	Global variables
--------------------------------------------------------------------------------------------------- */

var $doc = $( document ),
    $win = $( window ),
    winHeight = $win.height(),
    winWidth = $win.width();

var viewport = {};
	viewport.top = $win.scrollTop();
	viewport.bottom = viewport.top + $win.height();


/*	-----------------------------------------------------------------------------------------------
	Helper functions
--------------------------------------------------------------------------------------------------- */

/* Output AJAX errors ------------------------ */

function shiverAJAXErrors( jqXHR, exception ) {
	var message = '';
	if ( jqXHR.status === 0 ) {
		message = 'Not connect.n Verify Network.';
	} else if ( jqXHR.status == 404 ) {
		message = 'Requested page not found. [404]';
	} else if ( jqXHR.status == 500 ) {
		message = 'Internal Server Error [500].';
	} else if ( exception === 'parsererror' ) {
		message = 'Requested JSON parse failed.';
	} else if ( exception === 'timeout' ) {
		message = 'Time out error.';
	} else if ( exception === 'abort' ) {
		message = 'Ajax request aborted.';
	} else {
		message = 'Uncaught Error.n' + jqXHR.responseText;
	}
	console.log( 'AJAX ERROR:' + message );
}

/* Toggle an attribute ----------------------- */

function shiverToggleAttribute( $element, attribute, trueVal, falseVal ) {

	if ( typeof trueVal === 'undefined' ) { trueVal = true; }
	if ( typeof falseVal === 'undefined' ) { falseVal = false; }

	if ( $element.attr( attribute ) !== trueVal ) {
		$element.attr( attribute, trueVal );
	} else {
		$element.attr( attribute, falseVal );
	}
}


/*	-----------------------------------------------------------------------------------------------
	Interval Scroll
--------------------------------------------------------------------------------------------------- */

shiver.intervalScroll = {

	init: function() {

		didScroll = false;

		// Check for the scroll event
		$win.on( 'scroll load', function() {
			didScroll = true;
		} );

		// Once every 250ms, check if we have scrolled, and if we have, do the intensive stuff
		setInterval( function() {
			if ( didScroll ) {
				didScroll = false;

				// When this triggers, we know that we have scrolled
				$win.triggerHandler( 'did-interval-scroll' );

			}

		}, 250 );

	},

} // shiver.intervalScroll


/*	-----------------------------------------------------------------------------------------------
	Resize End Event
--------------------------------------------------------------------------------------------------- */

shiver.resizeEnd = {

	init: function() {

		var resizeTimer;

		$win.on( 'resize', function(e) {

			clearTimeout( resizeTimer );

			resizeTimer = setTimeout( function() {

				// Trigger this at the end of screen resizing
				$win.triggerHandler( 'resize-end' );

			}, 250 );

		} );

	},

} // shiver.resizeEnd


/*	-----------------------------------------------------------------------------------------------
	Toggles
--------------------------------------------------------------------------------------------------- */

shiver.toggles = {

	init: function() {

		// Do the toggle
		shiver.toggles.toggle();

		// Check for toggle/untoggle on resize
		shiver.toggles.resizeCheck();

		// Check for untoggle on escape key press
		shiver.toggles.untoggleOnEscapeKeyPress();

	},

	// Do the toggle
	toggle: function() {

		$( '*[data-toggle-target]' ).live( 'click', function( e ) {

			// Get our targets
			var $toggle = $( this ),
				targetString = $( this ).data( 'toggle-target' );

			if ( targetString == 'next' ) {
				var $target = $toggle.next();
			} else {
				var $target = $( targetString );
			}

			// Trigger events on the toggle targets before they are toggled
			if ( $target.is( '.active' ) ) {
				$target.trigger( 'toggle-target-before-active' );
			} else {
				$target.trigger( 'toggle-target-before-inactive' );
			}

			// Get the class to toggle, if specified
			var classToToggle = $toggle.data( 'class-to-toggle' ) ? $toggle.data( 'class-to-toggle' ) : 'active';

			// For cover modals, set a short timeout duration so the class animations have time to play out
			var timeOutTime = 0;

			if ( $target.hasClass( 'cover-modal' ) ) {
				var timeOutTime = 10;
			}

			setTimeout( function() {

				// Toggle the target of the clicked toggle
				if ( $toggle.data( 'toggle-type' ) == 'slidetoggle' ) {
					var duration = $toggle.data( 'toggle-duration' ) ? $toggle.data( 'toggle-duration' ) : 250;
					$target.slideToggle( duration );
				} else {
					$target.toggleClass( classToToggle );
				}

				// If the toggle target is 'next', only give the clicked toggle the active class
				if ( targetString == 'next' ) {
					$toggle.toggleClass( 'active' )

				// If not, toggle all toggles with this toggle target
				} else {
					$( '*[data-toggle-target="' + targetString + '"]' ).toggleClass( 'active' );
				}

				// Toggle aria-expanded on the target
				shiverToggleAttribute( $target, 'aria-expanded', 'true', 'false' );

				// Toggle aria-pressed on the toggle
				shiverToggleAttribute( $toggle, 'aria-pressed', 'true', 'false' );

				// Toggle body class
				if ( $toggle.data( 'toggle-body-class' ) ) {
					$( 'body' ).toggleClass( $toggle.data( 'toggle-body-class' ) );
				}

				// Check whether to lock the screen
				if ( $toggle.data( 'lock-screen' ) ) {
					shiver.scrollLock.setTo( true );
				} else if ( $toggle.data( 'unlock-screen' ) ) {
					shiver.scrollLock.setTo( false );
				} else if ( $toggle.data( 'toggle-screen-lock' ) ) {
					shiver.scrollLock.setTo();
				}

				// Check whether to set focus
				if ( $toggle.data( 'set-focus' ) ) {
					var $focusElement = $( $toggle.data( 'set-focus' ) );
					if ( $focusElement.length ) {
						if ( $toggle.is( '.active' ) ) {
							$focusElement.focus();
						} else {
							$focusElement.blur();
						}
					}
				}

				// Trigger the toggled event on the toggle target
				$target.triggerHandler( 'toggled' );

				// Trigger events on the toggle targets after they are toggled
				if ( $target.is( '.active' ) ) {
					$target.trigger( 'toggle-target-after-active' );
				} else {
					$target.trigger( 'toggle-target-after-inactive' );
				}

			}, timeOutTime );

			return false;

		} );
	},

	// Check for toggle/untoggle on screen resize
	resizeCheck: function() {

		if ( $( '*[data-untoggle-above], *[data-untoggle-below], *[data-toggle-above], *[data-toggle-below]' ).length ) {

			$win.on( 'resize', function() {

				var winWidth = $win.width(),
					$toggles = $( '.toggle' );

				$toggles.each( function() {

					$toggle = $( this );

					var unToggleAbove = $toggle.data( 'untoggle-above' ),
						unToggleBelow = $toggle.data( 'untoggle-below' ),
						toggleAbove = $toggle.data( 'toggle-above' ),
						toggleBelow = $toggle.data( 'toggle-below' );

					// If no width comparison is set, continue
					if ( ! unToggleAbove && ! unToggleBelow && ! toggleAbove && ! toggleBelow ) {
						return;
					}

					// If the toggle width comparison is true, toggle the toggle
					if (
						( ( ( unToggleAbove && winWidth > unToggleAbove ) ||
						( unToggleBelow && winWidth < unToggleBelow ) ) &&
						$toggle.hasClass( 'active' ) )
						||
						( ( ( toggleAbove && winWidth > toggleAbove ) ||
						( toggleBelow && winWidth < toggleBelow ) ) &&
						! $toggle.hasClass( 'active' ) )
					) {
						$toggle.trigger( 'click' );
					}

				} );

			} );

		}

	},

	// Close toggle on escape key press
	untoggleOnEscapeKeyPress: function() {

		$doc.keyup( function( e ) {
			if ( e.key === "Escape" ) {

				$( '*[data-untoggle-on-escape].active' ).each( function() {
					if ( $( this ).hasClass( 'active' ) ) {
						$( this ).trigger( 'click' );
					}
				} );

			}
		} );

	},

} // shiver.toggles


/*	-----------------------------------------------------------------------------------------------
	Cover Modals
--------------------------------------------------------------------------------------------------- */

shiver.coverModals = {

	init: function () {

		if ( $( '.cover-modal' ).length ) {

			// Handle cover modals when they're toggled
			shiver.coverModals.onToggle();

			// When toggled, untoggle if visitor clicks on the wrapping element of the modal
			shiver.coverModals.outsideUntoggle();

			// Close on escape key press
			shiver.coverModals.closeOnEscape();

			// Show a cover modal on load, if the query string says so
			shiver.coverModals.showOnLoadandClick();

			// Hide and show modals before and after their animations have played out
			shiver.coverModals.hideAndShowModals();

		}

	},

	// Handle cover modals when they're toggled
	onToggle: function() {

		$( '.cover-modal' ).on( 'toggled', function() {

			var $modal = $( this ),
				$body = $( 'body' );

			if ( $modal.hasClass( 'active' ) ) {
				$body.addClass( 'showing-modal' );
			} else {
				$body.removeClass( 'showing-modal' ).addClass( 'hiding-modal' );

				// Remove the hiding class after a delay, when animations have been run
				setTimeout ( function() {
					$body.removeClass( 'hiding-modal' );
				}, 500 );
			}
		} );

	},

	// Close modal on outside click
	outsideUntoggle: function() {

		$doc.live( 'click', function( e ) {

			var $target = $( e.target ),
				modal = '.cover-modal.active';

			if ( $target.is( modal ) ) {

				shiver.coverModals.untoggleModal( $target );

			}

		} );

	},

	// Close modal on escape key press
	closeOnEscape: function() {

		$doc.keyup( function( e ) {
			if ( e.key === "Escape" ) {
				$( '.cover-modal.active' ).each( function() {
					shiver.coverModals.untoggleModal( $( this ) );
				} );
			}
		} );

	},

	// Show modals on load
	showOnLoadandClick: function() {

		var key = 'modal';

		// Load based on query string
		if ( window.location.search.indexOf( key ) !== -1 ) {

			var modalTargetString = getQueryStringValue( key ),
				$modalTarget = $( '#' + modalTargetString + '-modal' );

			if ( modalTargetString && $modalTarget.length ) {
				setTimeout( function() {
					$modalTarget.addClass( 'active' ).triggerHandler( 'toggled' );
					shiver.scrollLock.setTo( true );
				}, 250 );
			}
		}

		// Check for modal matching querystring when clicking a link
		// Format: www.url.com?modal=modal-id
		$( 'a' ).live( 'click', function() {

			// Load based on query string
			if ( $( this ).attr( 'href' ) && $( this ).attr( 'href' ).indexOf( key ) !== -1 ) {

				var modalTargetString = getQueryStringValue( key, $( this ).attr( 'href' ) ),
					$modalTarget = $( '#' + modalTargetString );

				if ( modalTargetString && $modalTarget.length ) {

					$modalTarget.addClass( 'active' ).triggerHandler( 'toggled' );
					shiver.scrollLock.setTo( true );

					return false;

				}
			}

		} );

	},

	// Hide and show modals before and after their animations have played out
	hideAndShowModals: function() {

		var $modals = $( '.cover-modal' );

		// Show the modal
		$modals.on( 'toggle-target-before-inactive', function( e ) {
			if ( e.target != this ) return;

			$( this ).addClass( 'show-modal' );
		} );

		// Hide the modal after a delay, so animations have time to play out
		$modals.on( 'toggle-target-after-inactive', function( e ) {
			if ( e.target != this ) return;

			var $modal = $( this );
			setTimeout( function() {
				$modal.removeClass( 'show-modal' );
			}, 500 );
		} );

	},

	// Untoggle a modal
	untoggleModal: function( $modal ) {

		$modalToggle = false;

		// If the modal has specified the string (ID or class) used by toggles to target it, untoggle the toggles with that target string
		// The modal-target-string must match the string toggles use to target the modal
		if ( $modal.data( 'modal-target-string' ) ) {
			var modalTargetClass = $modal.data( 'modal-target-string' ),
				$modalToggle = $( '*[data-toggle-target="' + modalTargetClass + '"]' ).first();
		}

		// If a modal toggle exists, trigger it so all of the toggle options are included
		if ( $modalToggle && $modalToggle.length ) {
			$modalToggle.trigger( 'click' );

		// If one doesn't exist, just hide the modal
		} else {
			$modal.removeClass( 'active' );
		}

	}

} // shiver.coverModals


/*	-----------------------------------------------------------------------------------------------
	Element In View
--------------------------------------------------------------------------------------------------- */

shiver.elementInView = {

	init: function() {

		$targets = $( '.do-spot' );
		shiver.elementInView.run( $targets );

		// Rerun on AJAX content loaded
		$win.on( 'ajax-content-loaded', function() {
			$targets = $( '.do-spot' );
			shiver.elementInView.run( $targets );
		} );

	},

	run: function( $targets ) {

		if ( $targets.length ) {

			// Add class indicating the elements will be spotted
			$targets.each( function() {
				$( this ).addClass( 'will-be-spotted' );
			} );

			shiver.elementInView.handleFocus( $targets );
		}

	},

	handleFocus: function( $targets ) {

		// Get dimensions of window outside of scroll for performance
		$win.on( 'load resize orientationchange', function() {
			winHeight = $win.height();
		} );

		$win.on( 'resize orientationchange did-interval-scroll', function() {

			var winTop 		= $win.scrollTop();
				winBottom 	= winTop + winHeight;

			// Check for our targets
			$targets.each( function() {

				var $this = $( this );

				if ( shiver.elementInView.isVisible( $this, checkAbove = true ) ) {
					$this.addClass( 'spotted' ).triggerHandler( 'spotted' );
				}

			} );

		} );

	},

	// Determine whether the element is in view
	isVisible: function( $elem, checkAbove ) {

		if ( typeof checkAbove === 'undefined' ) {
			checkAbove = false;
		}

		var winHeight 				= $win.height();

		var docViewTop 				= $win.scrollTop(),
			docViewBottom			= docViewTop + winHeight,
			docViewLimit 			= docViewBottom - 50;

		var elemTop 				= $elem.offset().top,
			elemBottom 				= $elem.offset().top + $elem.outerHeight();

		// If checkAbove is set to true, which is default, return true if the browser has already scrolled past the element
		if ( checkAbove && ( elemBottom <= docViewBottom ) ) {
			return true;
		}

		// If not, check whether the scroll limit exceeds the element top
		return ( docViewLimit >= elemTop );

	}

} // shiver.elementInView


/*	-----------------------------------------------------------------------------------------------
	Fade Blocks
--------------------------------------------------------------------------------------------------- */

shiver.fadeBlocks = {

	init: function() {

		var scroll = window.requestAnimationFrame ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame ||
					window.msRequestAnimationFrame ||
					window.oRequestAnimationFrame ||
					// IE Fallback, you can even fallback to onscroll
					function( callback ) { window.setTimeout( callback, 1000/60 ) };

		function loop() {

			var windowOffset = window.pageYOffset;

			if ( windowOffset < $win.outerHeight() ) {
				$( '.fade-block' ).css({
					'opacity': 1 - ( windowOffset * 0.002 )
				} );
			}

			scroll( loop )

		}
		loop();

	},

} // shiver.fadeBlocks


/*	-----------------------------------------------------------------------------------------------
	Smooth Scroll
--------------------------------------------------------------------------------------------------- */

shiver.smoothScroll = {

	init: function() {

		// Scroll to on-page elements by hash
		$( 'a[href*="#"]' ).not( '[href="#"]' ).not( '[href="#0"]' ).on( 'click', function( event ) {
			if ( location.pathname.replace(/^\//, '' ) == this.pathname.replace(/^\//, '' ) && location.hostname == this.hostname ) {
				var $target = $( this.hash ).length ? $( this.hash ) : $( '[name=' + this.hash.slice(1) + ']' );
				shiver.smoothScroll.scrollToTarget( $target, $( this ) );
			}
		} );

		// Scroll to elements specified with a data attribute
		$( '*[data-scroll-to]' ).on( 'click', function( event ) {
			var $target = $( $( this ).data( 'scroll-to' ) );
			shiver.smoothScroll.scrollToTarget( $target, $( this ) );
		} );

	},

	// Scroll to target
	scrollToTarget: function( $target, $clickElem ) {

		if ( $target.length ) {

			event.preventDefault();

			var additionalOffset 	= 0,
				scrollSpeed			= 500;

			// Get options
			if ( $clickElem && $clickElem.length ) {
				additionalOffset 	= $clickElem.data( 'additional-offset' ) ? $clickElem.data( 'additional-offset' ) : additionalOffset,
				scrollSpeed 		= $clickElem.data( 'scroll-speed' ) ? $clickElem.data( 'scroll-speed' ) : scrollSpeed;
			}

			// Determine offset
			var originalOffset = $target.offset().top;

			// Special handling of scroll offset when scroll locked
			if ( $( 'html' ).attr( 'scroll-lock-top' ) ) {
				var originalOffset = parseInt( $( 'html' ).attr( 'scroll-lock-top' ) ) + $target.offset().top;
			}

			// If the header is sticky, subtract its height from the offset
			if ( $( '.header-inner.stick-me' ).length ) {
				var originalOffset = originalOffset - $( '.header-inner.stick-me' ).outerHeight();
			}

			// Close any parent modal before scrolling
			if ( $clickElem.closest( '.cover-modal' ).length ) {
				shiver.coverModals.untoggleModal( $clickElem.closest( '.cover-modal' ) );
			}

			// Add the additional offset
			var scrollOffset = originalOffset + additionalOffset;

			shiver.smoothScroll.scrollToPosition( scrollOffset, scrollSpeed );

		}

	},

	scrollToPosition: function( position, speed ) {

		$( 'html, body' ).animate( {
			scrollTop: position,
		}, speed, function() {
			$win.trigger( 'did-interval-scroll' );
		} );

	}

} // shiver.smoothScroll


/*	-----------------------------------------------------------------------------------------------
	Stick Me
--------------------------------------------------------------------------------------------------- */
shiver.stickMe = {

	init: function() {

		var $stickyElement = $( '.stick-me' );

		if ( $stickyElement.length ) {

			var stickyClass = 'is-sticky',
				stickyOffset = $stickyElement.scrollTop();

			// Our stand-in element for stickyElement while stickyElement is off on a scroll
			if ( ! $( '.sticky-adjuster' ).length ) {
				$stickyElement.before( '<div class="sticky-adjuster"></div>' );
			}

			// Stick it on resize, scroll and load
			$win.on( 'resize scroll load', function(){
				var stickyOffset = $( '.sticky-adjuster' ).offset().top;
				shiver.stickMe.stickIt( $stickyElement, stickyClass, stickyOffset );
			} );

			shiver.stickMe.stickIt( $stickyElement, stickyClass, stickyOffset );

		}

	},

	// Check whether to stick the element
	stickIt: function ( $stickyElement, stickyClass, stickyOffset ) {

		var winScroll = $win.scrollTop();

		if ( $stickyElement.css( 'display' ) != 'none' && winScroll > stickyOffset ) {

			// If a sticky edge element exists and we've scrolled past it, stick it
			if ( ! $stickyElement.hasClass( stickyClass ) ) {
				$stickyElement.addClass( stickyClass );
				$( '.sticky-adjuster' ).height( $stickyElement.outerHeight() ).css( 'margin-bottom', parseInt( $stickyElement.css( 'marginBottom' ) ) );
				if ( $stickyElement.is( '.header-inner' ) ) {
					$( 'body' ).addClass( 'header-is-sticky' );
				}
			}

		// If not, remove class and sticky-adjuster properties
		} else {
			shiver.stickMe.unstickIt( $stickyElement, stickyClass );
		}

	},

	unstickIt: function( $stickyElement, stickyClass ) {

		$stickyElement.removeClass( stickyClass );
		$( '.sticky-adjuster' ).height( 0 ).css( 'margin-bottom', '0' );

		if ( $stickyElement.is( '.header-inner' ) ) {
			$( 'body' ).removeClass( 'header-is-sticky' );
		}

	}

} // Stick Me


/*	-----------------------------------------------------------------------------------------------
	Intrinsic Ratio Embeds
--------------------------------------------------------------------------------------------------- */

shiver.instrinsicRatioVideos = {

	init: function() {

		shiver.instrinsicRatioVideos.makeFit();

		$win.on( 'resize fit-videos', function() {

			shiver.instrinsicRatioVideos.makeFit();

		} );

	},

	makeFit: function() {

		var vidSelector = "iframe, object, video";

		$( vidSelector ).each( function() {

			var $video = $( this ),
				$container = $video.parent(),
				iTargetWidth = $container.width();

			// Skip videos we want to ignore
			if ( $video.hasClass( 'intrinsic-ignore' ) || $video.parent().hasClass( 'intrinsic-ignore' ) ) {
				return true;
			}

			if ( ! $video.attr( 'data-origwidth' ) ) {

				// Get the video element proportions
				$video.attr( 'data-origwidth', $video.attr( 'width' ) );
				$video.attr( 'data-origheight', $video.attr( 'height' ) );

			}

			// Get ratio from proportions
			var ratio = iTargetWidth / $video.attr( 'data-origwidth' );

			// Scale based on ratio, thus retaining proportions
			$video.css( 'width', iTargetWidth + 'px' );
			$video.css( 'height', ( $video.attr( 'data-origheight' ) * ratio ) + 'px' );

		} );

	}

} // shiver.instrinsicRatioVideos


/*	-----------------------------------------------------------------------------------------------
	Scroll Lock
--------------------------------------------------------------------------------------------------- */

shiver.scrollLock = {

	init: function() {

		// Init variables
		window.scrollLocked = false,
		window.prevScroll = {
			scrollLeft : $win.scrollLeft(),
			scrollTop  : $win.scrollTop()
		},
		window.prevLockStyles = {},
		window.lockStyles = {
			'overflow-y' : 'scroll',
			'position'   : 'fixed',
			'width'      : '100%'
		};

		// Instantiate cache in case someone tries to unlock before locking
		shiver.scrollLock.saveStyles();

	},

	// Save context's inline styles in cache
	saveStyles: function() {

		var styleAttr = $( 'html' ).attr( 'style' ),
			styleStrs = [],
			styleHash = {};

		if ( ! styleAttr ) {
			return;
		}

		styleStrs = styleAttr.split( /;\s/ );

		$.each( styleStrs, function serializeStyleProp( styleString ) {
			if ( ! styleString ) {
				return;
			}

			var keyValue = styleString.split( /\s:\s/ );

			if ( keyValue.length < 2 ) {
				return;
			}

			styleHash[ keyValue[ 0 ] ] = keyValue[ 1 ];
		} );

		$.extend( prevLockStyles, styleHash );
	},

	// Lock the scroll (do not call this directly)
	lock: function() {

		var appliedLock = {};

		if ( scrollLocked ) {
			return;
		}

		// Save scroll state and styles
		prevScroll = {
			scrollLeft : $win.scrollLeft(),
			scrollTop  : $win.scrollTop()
		};

		shiver.scrollLock.saveStyles();

		// Compose our applied CSS, with scroll state as styles
		$.extend( appliedLock, lockStyles, {
			'left' : - prevScroll.scrollLeft + 'px',
			'top'  : - prevScroll.scrollTop + 'px'
		} );

		// Then lock styles and state
		$( 'html' ).css( appliedLock );
		$( 'html' ).attr( 'scroll-lock-top', prevScroll.scrollTop );
		$win.scrollLeft( 0 ).scrollTop( 0 );

		window.scrollLocked = true;
	},

	// Unlock the scroll (do not call this directly)
	unlock: function() {

		if ( ! window.scrollLocked ) {
			return;
		}

		// Revert styles and state
		$( 'html' ).attr( 'style', $( '<x>' ).css( prevLockStyles ).attr( 'style' ) || '' );
		$( 'html' ).attr( 'scroll-lock-top', '' );
		$win.scrollLeft( prevScroll.scrollLeft ).scrollTop( prevScroll.scrollTop );

		window.scrollLocked = false;
	},

	// Call this to lock or unlock the scroll
	setTo: function( on ) {

		// If an argument is passed, lock or unlock accordingly
		if ( arguments.length ) {
			if ( on ) {
				shiver.scrollLock.lock();
			} else {
				shiver.scrollLock.unlock();
			}
			// If not, toggle to the inverse state
		} else {
			if ( window.scrollLocked ) {
				shiver.scrollLock.unlock();
			} else {
				shiver.scrollLock.lock();
			}
		}

	},

} // shiver.scrollLock


/*	-----------------------------------------------------------------------------------------------
	Dynamic Screen Height
--------------------------------------------------------------------------------------------------- */

shiver.dynamicScreenHeight = {

	init: function() {

		var $screenHeight = $( '.screen-height' );

		$screenHeight.css( 'min-height', $win.innerHeight() );

		setTimeout( function() {
			$screenHeight.css( 'min-height', $win.innerHeight() );
		}, 500 );

		$win.on( 'resize', function() {
			$screenHeight.css( 'min-height', $win.innerHeight() );
		} );

	},

} // shiver.dynamicScreenHeight


/*	-----------------------------------------------------------------------------------------------
	Focus Management
--------------------------------------------------------------------------------------------------- */

shiver.focusManagement = {

	init: function() {

		// If the visitor tabs out of the main menu, return focus to the navigation toggle
		// Also, if the visitor tabs into a hidden element, move the focus to the element after the hidden element
		shiver.focusManagement.focusLoop();

	},

	focusLoop: function() {
		$( 'input, a, button' ).on( 'focus', function() {
			if ( $( '.menu-modal' ).is( '.active' ) ) {
				if ( ! $( this ).parents( '.menu-modal' ).length ) {
					$( '.nav-untoggle' ).focus();
				}
			} else if ( $( '.search-modal' ).is( '.active' ) ) {
				if ( ! $( this ).parents( '.search-modal' ).length ) {
					$( '.search-modal .search-field' ).focus();
				}
			}
		} );
	}

} // shiver.focusManagement


/*	-----------------------------------------------------------------------------------------------
	Main Menu
--------------------------------------------------------------------------------------------------- */

shiver.mainMenu = {

	init: function() {

		// If the current menu item is in a sub level, expand all the levels higher up on load
		shiver.mainMenu.expandLevel();

	},

	expandLevel: function() {
		var $activeMenuItem = $( '.main-menu .current-menu-item' );

		if ( $activeMenuItem.length !== false ) {
			$activeMenuItem.parents( 'li' ).each( function() {
				$subMenuToggle = $( this ).find( '.sub-menu-toggle' ).first();
				if ( $subMenuToggle.length ) {
					$subMenuToggle.trigger( 'click' );
				}
			} )
		}
	},

} // shiver.mainMenu


/*	-----------------------------------------------------------------------------------------------
	Load More
--------------------------------------------------------------------------------------------------- */

shiver.loadMore = {

	init: function() {

		var $pagination = $( '#pagination' );

		// First, check that there's a pagination
		if ( $pagination.length ) {

			// Default values for variables
			window.loading = false;
			window.lastPage = false;

			shiver.loadMore.prepare( $pagination );

		}

	},

	prepare: function( $pagination ) {

		// Get the query arguments from the pagination element
		var query_args = JSON.parse( $pagination.attr( 'data-query-args' ) );

		// If we're already at the last page, exit out here
		if ( query_args.paged == query_args.max_num_pages ) {
			$pagination.addClass( 'last-page' );
		} else {
			$pagination.removeClass( 'last-page' );
		}

		// Get the load more type (button or scroll)
		var loadMoreType = $pagination.data( 'pagination-type' );

		if ( ! loadMoreType ) {
			var loadMoreType = 'links';
		}

		// Do the appropriate load more detection, depending on the type
		if ( loadMoreType == 'scroll' ) {
			shiver.loadMore.detectScroll( $pagination, query_args );
		} else if ( loadMoreType == 'button' ) {
			shiver.loadMore.detectButtonClick( $pagination, query_args );
		}

	},

	// Load more on scroll
	detectScroll: function( $pagination, query_args ) {

		$win.on( 'did-interval-scroll', function() {

			// If it's the last page, or we're already loading, we're done here
			if ( lastPage || loading ) {
				return;
			}

			var paginationOffset 	= $pagination.offset().top,
				winOffset 			= $win.scrollTop() + $win.outerHeight();

			// If the bottom of the window is below the top of the pagination, start loading
			if ( ( winOffset > paginationOffset ) ) {
				shiver.loadMore.loadPosts( $pagination, query_args );
			}

		} );

	},

	// Load more on click
	detectButtonClick: function( $pagination, query_args ) {

		// Load on click
		$( '#load-more' ).on( 'click', function() {

			// Make sure we aren't already loading
			if ( loading ) {
				return;
			}

			shiver.loadMore.loadPosts( $pagination, query_args );
			return false;
		} );

	},

	// Load the posts
	loadPosts: function( $pagination, query_args ) {

		// We're now loading
		loading = true;
		$pagination.addClass( 'loading' ).removeClass( 'last-page' );

		// Increment paged to indicate another page has been loaded
		query_args.paged++;

		// Prepare the query args for submission
		var json_query_args = JSON.stringify( query_args );

		$.ajax({
			url: shiver_ajax_load_more.ajaxurl,
			type: 'post',
			data: {
				action: 'shiver_ajax_load_more',
				json_data: json_query_args
			},
			success: function( result ) {

				// Get the results
				var $result = $( result ),
					$articleWrapper = $( $pagination.data( 'load-more-target' ) );

				// If there are no results, we're at the last page
				if ( ! $result.length ) {
					loading = false;
					$articleWrapper.addClass( 'no-results' );
					$pagination.addClass( 'last-page' ).removeClass( 'loading' );
				}

				if ( $result.length ) {

					$articleWrapper.removeClass( 'no-results' );

					// Wait for the images to load
					$result.imagesLoaded( function() {

						// Append the results
						$articleWrapper.append( $result );

						$win.triggerHandler( 'ajax-content-loaded' );
						$win.triggerHandler( 'did-interval-scroll' );

						// Update history
						shiver.loadMore.updateHistory( query_args.paged );

						// We're now finished with the loading
						loading = false;
						$pagination.removeClass( 'loading' );

						// If that was the last page, make sure we don't check for any more
						if ( query_args.paged == query_args.max_num_pages ) {
							$pagination.addClass( 'last-page' );
							lastPage = true;
							return;
						} else {
							$pagination.removeClass( 'last-page' );
							lastPage = false;
						}

					} );

				}

			},

			error: function( jqXHR, exception ) {
				shiverAJAXErrors( jqXHR, exception );
			}
		} );

	},

	// Update browser history
    updateHistory: function( paged ) {

		var newUrl,
			currentUrl = document.location.href;

		// If currentUrl doesn't end with a slash, append one
		if ( currentUrl.substr( currentUrl.length - 1 ) !== '/' ) {
			currentUrl += '/';
		}

		var hasPaginationRegexp = new RegExp( '^(.*/page)/[0-9]*/(.*$)' );

		if ( hasPaginationRegexp.test( currentUrl ) ) {
			newUrl = currentUrl.replace( hasPaginationRegexp, '$1/' + paged + '/$2' );
		} else {
			var beforeSearchReplaceRegexp = new RegExp( '^([^?]*)(\\??.*$)' );
			newUrl = currentUrl.replace( beforeSearchReplaceRegexp, '$1page/' + paged + '/$2' );
		}

		history.pushState( {}, '', newUrl );

	}

} // shiver.loadMore


/*	-----------------------------------------------------------------------------------------------
	Function Calls
--------------------------------------------------------------------------------------------------- */

$doc.ready( function() {

	shiver.intervalScroll.init();				// Check for scroll on an interval
	shiver.resizeEnd.init();					// Trigger event at end of resize
	shiver.toggles.init();						// Handle toggles
	shiver.coverModals.init();					// Handle cover modals
	shiver.elementInView.init();				// Check if elements are in view
	shiver.fadeBlocks.init();					// Fade elements on scroll
	shiver.instrinsicRatioVideos.init();		// Retain aspect ratio of videos on window resize
	shiver.smoothScroll.init();				// Smooth scroll to anchor link or a specific element
	shiver.stickMe.init();						// Stick elements on scroll
	shiver.scrollLock.init();					// Scroll Lock
	shiver.mainMenu.init();					// Main Menu
	shiver.focusManagement.init();				// Focus Management
	shiver.dynamicScreenHeight.init();			// Dynamic Screen Height
	shiver.loadMore.init();					// Load More

} );