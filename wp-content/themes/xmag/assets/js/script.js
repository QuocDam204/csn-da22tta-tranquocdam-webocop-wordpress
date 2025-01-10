/**
 * File script.js.
 *
 */

( function() {

	function toggleMenu() {
		const button = document.getElementById( 'menu-toggle' );
		if (!button) {
			return;
		}

		const mobileSidebar = document.getElementById( 'mobile-sidebar' );
		const mobileMenu    = mobileSidebar.querySelector( 'ul' );
		const body          = document.body;

		button.addEventListener( 'click', () => {
			if ( mobileSidebar.classList.contains( 'toggled-on' ) ) {
				button.setAttribute( 'aria-expanded', 'false' );
				mobileMenu.setAttribute( 'aria-expanded', 'false' );
			} else {
				button.setAttribute( 'aria-expanded', 'true' );
				mobileMenu.setAttribute( 'aria-expanded', 'true' );
			}
			mobileSidebar.classList.toggle( 'toggled-on' );
			body.classList.toggle( 'mobile-menu-active' );
		} );
	}

	function toggleSubmenu() {
		const mobileNav = document.getElementById( 'mobile-navigation' );
		if (!mobileNav) {
			return;
		}

		const buttons = [...mobileNav.querySelectorAll( '.dropdown-toggle' )];

		buttons.forEach( button => {
			button.addEventListener( 'click', e => {
				e.preventDefault();
				const a = button.previousElementSibling, li = a.closest( 'li' );
				if ( li.classList.contains( 'is-open' ) ) {
					button.setAttribute( 'aria-expanded', 'false' );
					a.setAttribute( 'aria-expanded', 'false' );
				} else {
					button.setAttribute( 'aria-expanded', 'true' );
					a.setAttribute( 'aria-expanded', 'true' );
				}
				li.classList.toggle( 'is-open' );
			} );
		} );
	}

	function scrollToTop() {
		const button = document.getElementById( 'scroll-up' );
		if (!button) {
			return;
		}

		window.addEventListener( 'scroll', () => {
			if ( window.scrollY > 480 ) {
				button.style.display = 'block';
			} else {
				button.style.display = 'none';
			}
		} );

		button.addEventListener( 'click', e => {
			e.preventDefault();
			window.scrollTo( { top: 0, left: 0, behavior: 'smooth' } );
		} );
	}

	function getAdminBarHeight() {
		const adminBar = document.getElementById( 'wpadminbar' );
		if (!adminBar) {
			return;
		}

		adminBarHeight = adminBar.getBoundingClientRect().height;
		return Number( adminBarHeight );
	}

	function stickyHeader() {
		const header       = document.getElementById( 'masthead' );
		const headerSticky = header.querySelector( '.sticky-header' );

		if (!headerSticky) {
			return;
		}

		var headerHeight  = Number( headerSticky.getBoundingClientRect().height );
		var mobileSidebar = document.getElementById( 'mobile-sidebar' );
		let isMobile      = window.matchMedia("only screen and (max-width: 600px)").matches;

		// Fires on resize of Browser
		window.onresize = function(event) {
			isMobile = window.matchMedia("only screen and (max-width: 600px)").matches;
		};

		window.addEventListener( 'scroll', event => {
			const { scrollTop } = event.target.scrollingElement;
			headerSticky.classList.toggle( 'fixed', scrollTop >= headerHeight );
			if (  scrollTop >= headerHeight ) {
				document.body.style.setProperty( 'padding-top', headerHeight + 'px' );
				if ( getAdminBarHeight() ) {
					if ( isMobile ) {
						mobileSidebar.style.setProperty( 'top', '50px' );
					} else {
						headerSticky.style.setProperty( 'top', getAdminBarHeight() + 'px' );
					}
				}
			} else {
				document.body.style.removeProperty( 'padding-top' );
				if ( getAdminBarHeight() ) {
					mobileSidebar.style.removeProperty( 'top' );
					headerSticky.style.removeProperty( 'top' );
				}
			}
		} );
	}

	toggleMenu();
	toggleSubmenu();
	scrollToTop();
	stickyHeader();

}() );
