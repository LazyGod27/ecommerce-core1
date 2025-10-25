/**
 * Global Logo Size Enforcer for iMarket
 * Ensures all logos are exactly 80px max-height across all HTML pages
 */

(function() {
    'use strict';
    
    // Configuration
    const LOGO_MAX_HEIGHT = 80; // pixels
    const LOGO_SELECTORS = [
        '.logo img',
        'header .logo img',
        'img[alt="IMARKET PH Logo"]',
        'img[src*="logo.png"]'
    ];
    
    /**
     * Apply consistent logo sizing
     */
    function enforceLogoSize() {
        LOGO_SELECTORS.forEach(selector => {
            const logos = document.querySelectorAll(selector);
            logos.forEach(logo => {
                // Apply consistent sizing
                logo.style.setProperty('max-height', LOGO_MAX_HEIGHT + 'px', 'important');
                logo.style.setProperty('height', 'auto', 'important');
                logo.style.setProperty('width', 'auto', 'important');
                logo.style.setProperty('display', 'block', 'important');
                logo.style.setProperty('margin-top', '6px', 'important');
                logo.style.setProperty('margin-left', '-30px', 'important');
                
                // Add class for additional CSS enforcement
                logo.classList.add('logo-size-enforced');
            });
        });
    }
    
    /**
     * Initialize logo size enforcement
     */
    function init() {
        // Apply immediately
        enforceLogoSize();
        
        // Apply when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', enforceLogoSize);
        } else {
            enforceLogoSize();
        }
        
        // Apply with delays to catch dynamic content
        setTimeout(enforceLogoSize, 100);
        setTimeout(enforceLogoSize, 500);
        setTimeout(enforceLogoSize, 1000);
        
        // Re-apply after any dynamic content changes
        const observer = new MutationObserver(function(mutations) {
            let shouldReapply = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if any new logo elements were added
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.matches && node.matches('img[src*="logo"]')) {
                                shouldReapply = true;
                            } else if (node.querySelectorAll) {
                                const logos = node.querySelectorAll('img[src*="logo"]');
                                if (logos.length > 0) {
                                    shouldReapply = true;
                                }
                            }
                        }
                    });
                }
            });
            
            if (shouldReapply) {
                setTimeout(enforceLogoSize, 100);
            }
        });
        
        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Also re-apply on window resize (in case responsive styles interfere)
        window.addEventListener('resize', function() {
            setTimeout(enforceLogoSize, 100);
        });
    }
    
    // Initialize when script loads
    init();
    
    // Export for manual triggering if needed
    window.enforceLogoSize = enforceLogoSize;
    
})();
