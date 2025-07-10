

// Erase the current page content and set a black background
document.body.innerHTML = '';
document.body.style.backgroundColor = 'black';
document.body.style.overflow = 'hidden';

// Create a container for the hacker-style text
const container = document.createElement('div');
container.style.fontFamily = '"Courier New", Courier, monospace';
container.style.color = '#00ff00'; // Bright green
container.style.textAlign = 'center';
container.style.position = 'absolute';
container.style.top = '50%';
container.style.left = '50%';
container.style.transform = 'translate(-50%, -50%)';
container.style.fontSize = '1.2em';
container.style.whiteSpace = 'pre'; // Preserve spaces and newlines for ASCII art
document.body.appendChild(container);

// The message to be typed out, including some ASCII art
const message = `
   .--.
  |o_o |
  |:_/ |
 //   \ \
(|     | )
/'\_   _/`\
\___)=(___/\n
[+] SYSTEM BREACHED
[+] ACCESS GRANTED
> You have been pwned by Usman_`;

let i = 0;
function typeWriter() {
  if (i < message.length) {
    // Append character by character
    container.innerHTML += message.charAt(i);
    i++;
    setTimeout(typeWriter, 60); // Adjust typing speed (milliseconds)
  } else {
    // Add a blinking cursor at the end
    const cursor = document.createElement('span');
    cursor.innerHTML = '█';
    cursor.style.animation = 'blink 1s step-end infinite';
    container.appendChild(cursor);

    // Define the blinking animation
    const style = document.createElement('style');
    style.innerHTML = `
      @keyframes blink {
        from, to { opacity: 1; }
        50% { opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  }
}

// Start the effect
typeWriter();

