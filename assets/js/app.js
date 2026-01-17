/* ============================================
   COURSES APP - SIMPLE VANILLA JAVASCRIPT
   Dark Mode + Sidebar Navigation
   Beginner-friendly code with clear comments
   ============================================ */

// ============================================
// DOCUMENT READY - Wait for page to load
// ============================================
// This ensures all HTML elements are loaded before JavaScript runs
document.addEventListener('DOMContentLoaded', function() {
  console.log('Page loaded! JavaScript is ready.');
  
  // Initialize all functions when page loads
  initSidebarNavigation();
  initCardAnimations();
  initButtonHovers();
  initFormInteractions();
  initLiveSearch();
});

// ============================================
// FUNCTION: Initialize Sidebar Navigation
// ============================================
// This function handles sidebar link clicks and active state
function initSidebarNavigation() {
  // Find all sidebar navigation links
  const sidebarLinks = document.querySelectorAll('.sidebar-link');
  
  // Get current page URL to determine active link
  const currentPage = window.location.pathname;
  
  // Loop through each sidebar link
  sidebarLinks.forEach(function(link) {
    // Check if this link matches the current page
    const linkHref = link.getAttribute('href');
    
    // If link matches current page, add 'active' class
    if (linkHref && currentPage.includes(linkHref)) {
      link.classList.add('active');
    }
    
    // Add click event listener to each link
    link.addEventListener('click', function(e) {
      // Remove active class from all links
      sidebarLinks.forEach(function(otherLink) {
        otherLink.classList.remove('active');
      });
      
      // Add active class to clicked link
      this.classList.add('active');
      
      console.log('Sidebar link clicked:', this.textContent);
    });
  });
  
  console.log('Sidebar navigation initialized. Found ' + sidebarLinks.length + ' links.');
}

// ============================================
// FUNCTION: Initialize Card Animations
// ============================================
// This function adds fade-in animations and hover effects to course cards
function initCardAnimations() {
  // Find all cards with class 'card'
  const cards = document.querySelectorAll('.card');
  
  // Loop through each card
  cards.forEach(function(card, index) {
    // Add fade-in animation with staggered delay
    // First card appears immediately, second after 0.1s, third after 0.2s, etc.
    card.style.animationDelay = (index * 0.1) + 's';
    card.classList.add('fade-in');
    
    // Add smooth hover effect
    card.addEventListener('mouseenter', function() {
      // Card lifts up slightly on hover
      this.style.transform = 'translateY(-6px)';
    });
    
    card.addEventListener('mouseleave', function() {
      // Return card to original position
      this.style.transform = 'translateY(0)';
    });
  });
  
  console.log('Card animations initialized. Found ' + cards.length + ' cards.');
}

// ============================================
// FUNCTION: Initialize Button Hover Effects
// ============================================
// This function adds smooth hover effects to buttons
function initButtonHovers() {
  // Find all buttons with class 'btn'
  const buttons = document.querySelectorAll('.btn');
  
  // Loop through each button
  buttons.forEach(function(button) {
    // Add click effect
    button.addEventListener('click', function() {
      // Briefly scale down to show click feedback
      this.style.transform = 'scale(0.95)';
      
      // After 150ms, return to normal size
      setTimeout(function() {
        button.style.transform = 'scale(1)';
      }, 150);
      
      console.log('Button clicked:', this.textContent.trim());
    });
  });
  
  console.log('Button hover effects initialized. Found ' + buttons.length + ' buttons.');
}

// ============================================
// FUNCTION: Initialize Form Interactions
// ============================================
// This function adds helpful interactions to form inputs
function initFormInteractions() {
  // Find all form input fields
  const inputs = document.querySelectorAll('.form-control');
  
  // Loop through each input
  inputs.forEach(function(input) {
    // When user clicks on input (focus event)
    input.addEventListener('focus', function() {
      // Input gets highlighted border
      this.style.borderColor = 'var(--accent-primary)';
      console.log('Input field focused');
    });
    
    // When user clicks away from input (blur event)
    input.addEventListener('blur', function() {
      // Reset border color (CSS handles the default state)
      this.style.borderColor = '';
    });
    
    // When user types in the input
    input.addEventListener('input', function() {
      // Add a subtle animation when typing (optional)
      this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.2)';
      
      // Remove shadow after a short delay
      setTimeout(function() {
        input.style.boxShadow = '';
      }, 200);
    });
  });
  
  console.log('Form interactions initialized. Found ' + inputs.length + ' input fields.');
}

// ============================================
// FUNCTION: Toggle Sidebar (Mobile)
// ============================================
// This function shows/hides sidebar on mobile devices
function toggleSidebar() {
  // Find the sidebar element
  const sidebar = document.querySelector('.sidebar');
  
  if (sidebar) {
    // Toggle the 'open' class on sidebar
    sidebar.classList.toggle('open');
    
    const isOpen = sidebar.classList.contains('open');
    console.log('Sidebar toggled:', isOpen ? 'Open' : 'Closed');
  }
}

// ============================================
// FUNCTION: Show Section (For future use)
// ============================================
// This function can show/hide sections (if needed for single-page app)
// Parameters:
//   - sectionId: The ID of the section to show
function showSection(sectionId) {
  // Find the section element
  const section = document.getElementById(sectionId);
  
  if (section) {
    // Show the section with fade-in animation
    section.style.display = 'block';
    section.style.opacity = '0';
    section.style.transition = 'opacity 0.3s ease';
    
    // Trigger fade-in after display changes
    setTimeout(function() {
      section.style.opacity = '1';
    }, 10);
    
    console.log('Section shown:', sectionId);
  } else {
    console.log('Section not found:', sectionId);
  }
}

// ============================================
// FUNCTION: Hide Section (For future use)
// ============================================
// This function hides a section with fade-out animation
// Parameters:
//   - sectionId: The ID of the section to hide
function hideSection(sectionId) {
  // Find the section element
  const section = document.getElementById(sectionId);
  
  if (section) {
    // Fade out animation
    section.style.opacity = '0';
    
    // Hide completely after animation
    setTimeout(function() {
      section.style.display = 'none';
    }, 300);
    
    console.log('Section hidden:', sectionId);
  } else {
    console.log('Section not found:', sectionId);
  }
}

// ============================================
// NOTE FOR PRESENTATION
// ============================================
// This JavaScript file demonstrates:
// 1. Simple event listeners (click, hover, focus, blur)
// 2. DOM manipulation (changing classes, styles)
// 3. Basic animations (fade-in, transform)
// 4. LocalStorage for preferences (dark mode)
// 5. Clean, readable code with comments
// 6. No frameworks - pure vanilla JavaScript
// 
// All functions are easy to explain and understand!

// ============================================
// LIVE SEARCH (AJAX)
// ============================================
function debounce(fn, delay) {
  let timer = null;
  return function(...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

function initLiveSearch() {
  const input = document.getElementById('search-input');
  const container = document.getElementById('live-results');
  if (!input || !container) return;

  const doSearch = debounce(function() {
    const q = input.value.trim();
    const url = 'search_api.php?q=' + encodeURIComponent(q);

    fetch(url)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          container.innerHTML = '<div class="col-12 text-danger">Error: ' + data.error + '</div>';
          return;
        }
        renderLiveResults(data, container);
      })
      .catch(err => {
        container.innerHTML = '<div class="col-12 text-danger">Network error</div>';
        console.error(err);
      });
  }, 300);

  input.addEventListener('input', doSearch);
}

function renderLiveResults(items, container) {
  if (!items || items.length === 0) {
    container.innerHTML = '<div class="col-12 text-muted">No results.</div>';
    return;
  }

  const html = items.map(item => {
    const desc = item.description ? (item.description.length > 100 ? item.description.substring(0,100) + '...' : item.description) : '';
    return `
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">${escapeHtml(item.title)}</h5>
            <p class="card-text text-secondary">${escapeHtml(desc)}</p>
          </div>
          <div class="card-footer">
            <a href="course.php?id=${encodeURIComponent(item.id)}" class="btn btn-outline w-100">View course</a>
          </div>
        </div>
      </div>`;
  }).join('');

  container.innerHTML = html;
  initCardAnimations();
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}
