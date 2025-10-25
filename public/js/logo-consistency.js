/**
 * Logo Consistency Enforcer
 * Ensures all iMarket logos have the same size across all pages
 */

(function() {
    'use strict';
    
    // Configuration
    const LOGO_MAX_HEIGHT = 80; // pixels
    const LOGO_SELECTORS = [
        'img[src*="logo.png"]',
        'img[alt*="IMARKET"]',
        'img[alt*="iMarket"]',
        'img[alt*="logo"]',
        '.logo img',
        'header .logo img',
        '.header .logo img'
    ];
    
    /**
     * Apply consistent logo sizing
     */
    function enforceLogoConsistency() {
        LOGO_SELECTORS.forEach(selector => {
            const logos = document.querySelectorAll(selector);
            logos.forEach(logo => {
                // Apply consistent sizing
                logo.style.maxHeight = LOGO_MAX_HEIGHT + 'px';
                logo.style.height = 'auto';
                logo.style.width = 'auto';
                logo.style.display = 'block';
                
                // Add class for additional CSS enforcement
                logo.classList.add('logo-size-enforced');
                
                // Remove any conflicting Tailwind classes
                logo.classList.remove('w-16', 'h-16', 'w-20', 'h-20', 'w-24', 'h-24', 'w-32', 'h-32');
            });
        });
    }
    
    /**
     * Initialize logo consistency enforcement
     */
    function init() {
        // Apply on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', enforceLogoConsistency);
        } else {
            enforceLogoConsistency();
        }
        
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
                setTimeout(enforceLogoConsistency, 100);
            }
        });
        
        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Also re-apply on window resize (in case responsive styles interfere)
        window.addEventListener('resize', function() {
            setTimeout(enforceLogoConsistency, 100);
        });
    }
    
    // Initialize when script loads
    init();
    
    // Export for manual triggering if needed
    window.enforceLogoConsistency = enforceLogoConsistency;
    
})();
