document.addEventListener('DOMContentLoaded', () => {
    const answerButtons = document.querySelectorAll('.toggle-answer');

    answerButtons.forEach(button => {
        button.addEventListener('click', () => {
            const answer = button.previousElementSibling;
            const isVisible = answer.style.display === 'block';

            answer.style.display = isVisible ? 'none' : 'block';
            button.textContent = isVisible ? 'Show Answer' : 'Hide Answer';
        });
    });
});