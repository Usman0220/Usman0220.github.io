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
