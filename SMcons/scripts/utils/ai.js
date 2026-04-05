
async function sendMessage() {
  const input = document.getElementById("userMessage");
  const message = input.value;
  if (!message) return;

  
  const chat = document.getElementById("chat-messages");
  chat.innerHTML += `<div><b>You:</b> ${message}</div>`;
  input.value = "";

  
  const response = await fetch("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=AIzaSyAUmewI1OrEfyVStVQuwcV0xW2jB7twJJ8", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      contents: [{ parts: [{ text: message }] }]
    })
  });

  const data = await response.json();
  const reply = data.candidates[0].content.parts[0].text;

  
  chat.innerHTML += `<div><b>Gemini:</b> ${reply}</div>`;
}
