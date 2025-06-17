// Blog JavaScript functionality
document.addEventListener("DOMContentLoaded", () => {
    // Category filtering
    const categoryButtons = document.querySelectorAll(".category-btn")
    const articles = document.querySelectorAll(".article-card")
  
    categoryButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const category = this.dataset.category
  
        // Update active button
        categoryButtons.forEach((btn) => btn.classList.remove("active"))
        this.classList.add("active")
  
        // Filter articles
        articles.forEach((article) => {
          if (category === "all" || article.dataset.category === category) {
            article.style.display = "block"
            article.style.animation = "fadeInUp 0.6s ease forwards"
          } else {
            article.style.display = "none"
          }
        })
      })
    })
  
    // Search functionality
    const searchInput = document.querySelector(".search-input")
  
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
  
      articles.forEach((article) => {
        const title = article.querySelector(".article-title").textContent.toLowerCase()
        const excerpt = article.querySelector(".article-excerpt").textContent.toLowerCase()
  
        if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
          article.style.display = "block"
        } else {
          article.style.display = "none"
        }
      })
    })
  
    // Newsletter form
    const newsletterForm = document.querySelector(".newsletter-form")
  
    newsletterForm.addEventListener("submit", function (e) {
      e.preventDefault()
      const email = this.querySelector('input[type="email"]').value
  
      // Simulate newsletter subscription
      alert(`Merci ! Vous êtes maintenant abonné avec l'email : ${email}`)
      this.reset()
    })
  
    // Article click handlers
    articles.forEach((article) => {
      article.addEventListener("click", function () {
        // Simulate article navigation
        const title = this.querySelector(".article-title").textContent
        console.log(`Navigation vers l'article : ${title}`)
        // Here you would typically navigate to the full article page
      })
    })
  
    // Popular articles click handlers
    const popularArticles = document.querySelectorAll(".popular-article")
  
    popularArticles.forEach((article) => {
      article.addEventListener("click", function () {
        const title = this.querySelector("h4").textContent
        console.log(`Navigation vers l'article populaire : ${title}`)
      })
    })
  
    // Tags click handlers
    const tags = document.querySelectorAll(".tag")
  
    tags.forEach((tag) => {
      tag.addEventListener("click", function () {
        const tagText = this.textContent
        searchInput.value = tagText
        searchInput.dispatchEvent(new Event("input"))
      })
    })
  
    // Pagination handlers
    const paginationNumbers = document.querySelectorAll(".pagination-number")
  
    paginationNumbers.forEach((number) => {
      number.addEventListener("click", function () {
        paginationNumbers.forEach((num) => num.classList.remove("active"))
        this.classList.add("active")
  
        // Simulate page change
        window.scrollTo({ top: 0, behavior: "smooth" })
      })
    })
  
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault()
        const target = document.querySelector(this.getAttribute("href"))
        if (target) {
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          })
        }
      })
    })
  
    // Lazy loading for images (simple implementation)
    const images = document.querySelectorAll("img")
  
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target
          img.style.opacity = "0"
          img.style.transition = "opacity 0.3s ease"
  
          img.onload = () => {
            img.style.opacity = "1"
          }
  
          observer.unobserve(img)
        }
      })
    })
  
    images.forEach((img) => imageObserver.observe(img))
  })
  
  // Add scroll effect to header
  window.addEventListener("scroll", () => {
    const header = document.querySelector(".header")
    if (window.scrollY > 100) {
      header.style.background = "rgba(255, 255, 255, 0.95)"
      header.style.backdropFilter = "blur(10px)"
      header.style.boxShadow = "0 2px 20px rgba(0, 0, 0, 0.1)"
    } else {
      header.style.background = "white"
      header.style.backdropFilter = "none"
      header.style.boxShadow = "none"
    }
  })
  