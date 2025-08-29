document.addEventListener('DOMContentLoaded', () => {
    const answerButtons = document.querySelectorAll('.toggle-answer');
    const searchBar = document.getElementById('search-bar');
    const mnemonicCards = document.querySelectorAll('.mnemonic-card');

    answerButtons.forEach(button => {
        button.addEventListener('click', () => {
            const answer = button.previousElementSibling;
            const isVisible = answer.style.display === 'block';

            answer.style.display = isVisible ? 'none' : 'block';
            button.textContent = isVisible ? 'Show Answer' : 'Hide Answer';
        });
    });

    searchBar.addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();

        mnemonicCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const phrase = card.querySelector('.mnemonic-phrase').textContent.toLowerCase();

            if (title.includes(searchTerm) || phrase.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});


// Hamburger menu functionality
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // Close menu when clicking on nav links
    document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    }));

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        }
    });
}

