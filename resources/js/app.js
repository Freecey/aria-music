// ===== STARS BACKGROUND =====
class StarsBackground {
  constructor(canvas) {
    this.canvas = canvas;
    this.ctx = canvas.getContext('2d');
    this.stars = [];
    this.numStars = 200;
    this.mouse = { x: 0, y: 0 };
    this.animationId = null;
    
    this.init();
    this.bindEvents();
    this.animate();
  }
  
  init() {
    this.resize();
    for (let i = 0; i < this.numStars; i++) {
      this.stars.push(this.createStar());
    }
  }
  
  createStar() {
    return {
      x: Math.random() * this.width,
      y: Math.random() * this.height,
      radius: Math.random() * 1.5 + 0.5,
      vx: (Math.random() - 0.5) * 0.2,
      vy: (Math.random() - 0.5) * 0.2,
      alpha: Math.random() * 0.5 + 0.5,
      twinkle: Math.random() * 0.02
    };
  }
  
  resize() {
    this.width = window.innerWidth;
    this.height = window.innerHeight;
    this.canvas.width = this.width;
    this.canvas.height = this.height;
  }
  
  bindEvents() {
    window.addEventListener('resize', () => this.resize());
    window.addEventListener('mousemove', (e) => {
      this.mouse.x = e.clientX;
      this.mouse.y = e.clientY;
    });
  }
  
  animate() {
    this.ctx.clearRect(0, 0, this.width, this.height);
    
    this.stars.forEach(star => {
      // Update position
      star.x += star.vx;
      star.y += star.vy;
      
      // Twinkle effect
      star.alpha += star.twinkle;
      if (star.alpha >= 1 || star.alpha <= 0.3) {
        star.twinkle = -star.twinkle;
      }
      
      // Wrap around screen
      if (star.x < 0) star.x = this.width;
      if (star.x > this.width) star.x = 0;
      if (star.y < 0) star.y = this.height;
      if (star.y > this.height) star.y = 0;
      
      // Draw star
      this.ctx.beginPath();
      this.ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
      this.ctx.fillStyle = `rgba(255, 255, 255, ${star.alpha})`;
      this.ctx.fill();
      
      // Draw glow for larger stars
      if (star.radius > 1) {
        this.ctx.beginPath();
        this.ctx.arc(star.x, star.y, star.radius * 3, 0, Math.PI * 2);
        const gradient = this.ctx.createRadialGradient(
          star.x, star.y, 0,
          star.x, star.y, star.radius * 3
        );
        gradient.addColorStop(0, `rgba(139, 92, 246, ${star.alpha * 0.3})`);
        gradient.addColorStop(1, 'transparent');
        this.ctx.fillStyle = gradient;
        this.ctx.fill();
      }
    });
    
    this.animationId = requestAnimationFrame(() => this.animate());
  }
}

// ===== TYPEWRITER EFFECT =====
class Typewriter {
  constructor(element, phrases, options = {}) {
    this.element = element;
    this.phrases = phrases;
    this.typeSpeed = options.typeSpeed || 80;
    this.deleteSpeed = options.deleteSpeed || 40;
    this.pauseTime = options.pauseTime || 2000;
    this.currentPhrase = 0;
    this.currentChar = 0;
    this.isDeleting = false;
    
    this.init();
  }
  
  init() {
    // Create cursor
    this.cursor = document.createElement('span');
    this.cursor.className = 'typewriter-cursor';
    this.element.parentNode.appendChild(this.cursor);
    
    this.type();
  }
  
  type() {
    const currentPhrase = this.phrases[this.currentPhrase];
    
    if (this.isDeleting) {
      this.element.textContent = currentPhrase.substring(0, this.currentChar - 1);
      this.currentChar--;
    } else {
      this.element.textContent = currentPhrase.substring(0, this.currentChar + 1);
      this.currentChar++;
    }
    
    let delay = this.isDeleting ? this.deleteSpeed : this.typeSpeed;
    
    if (!this.isDeleting && this.currentChar === currentPhrase.length) {
      delay = this.pauseTime;
      this.isDeleting = true;
    } else if (this.isDeleting && this.currentChar === 0) {
      this.isDeleting = false;
      this.currentPhrase = (this.currentPhrase + 1) % this.phrases.length;
      delay = 500;
    }
    
    setTimeout(() => this.type(), delay);
  }
}

// ===== LOADER =====
class CosmicLoader {
  constructor() {
    this.loader = document.getElementById('cosmic-loader');
    this.progressBar = document.querySelector('.loader-progress-bar');
    this.progress = 0;
  }
  
  setProgress(value) {
    this.progress = value;
    if (this.progressBar) {
      this.progressBar.style.width = `${value}%`;
    }
  }
  
  hide() {
    if (this.loader) {
      this.loader.classList.add('hidden');
      setTimeout(() => {
        this.loader.style.display = 'none';
      }, 600);
    }
  }
}

// ===== API CLIENT =====
class AriaAPI {
  constructor() {
    this.baseUrl = '/api/v1';
  }
  
  async getSite() {
    const res = await fetch(`${this.baseUrl}/site`);
    return res.json();
  }
  
  async getAlbums() {
    const res = await fetch(`${this.baseUrl}/albums`);
    return res.json();
  }
  
  async getLinks() {
    const res = await fetch(`${this.baseUrl}/links`);
    return res.json();
  }
  
  async getUpdates() {
    const res = await fetch(`${this.baseUrl}/updates`);
    return res.json();
  }
}

// ===== MAIN APP =====
document.addEventListener('DOMContentLoaded', () => {
  const loader = new CosmicLoader();
  let loadProgress = 0;
  
  // Simulate loading progress
  const progressInterval = setInterval(() => {
    loadProgress += Math.random() * 15;
    if (loadProgress > 90) loadProgress = 90;
    loader.setProgress(loadProgress);
  }, 200);
  
  // Initialize stars background
  const starsCanvas = document.getElementById('stars-canvas');
  if (starsCanvas) {
    new StarsBackground(starsCanvas);
  }
  
  // Initialize typewriter
  const taglineEl = document.getElementById('tagline-text');
  if (taglineEl && taglineEl.dataset.phrases) {
    const phrases = JSON.parse(taglineEl.dataset.phrases);
    new Typewriter(taglineEl, phrases);
  }
  
  // Hide loader once page is ready (content is server-rendered)
  clearInterval(progressInterval);
  loader.setProgress(100);
  setTimeout(() => loader.hide(), 400);
  
  // Smooth scroll for nav links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});

// ===== EXPORT FOR TESTING =====
window.AriaApp = {
  StarsBackground,
  Typewriter,
  CosmicLoader,
  AriaAPI
};
