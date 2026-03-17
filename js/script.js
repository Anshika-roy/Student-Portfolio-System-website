function renderList(elementId, items, fallbackText) {
	const list = document.getElementById(elementId);
	list.innerHTML = "";

	if (!items.length) {
		const li = document.createElement("li");
		li.innerText = fallbackText;
		list.appendChild(li);
		return;
	}

	items.forEach(function (item) {
		const li = document.createElement("li");
		li.innerText = item;
		list.appendChild(li);
	});
}

function displayPortfolio(user) {
	document.getElementById("display-name").innerText = user.name || "User Name";
	document.querySelector("#display-email span").innerText = user.email || "Not provided";
	document.querySelector("#display-phone span").innerText = user.phone || "Not provided";

	const skills = Array.isArray(user.skills) ? user.skills : [];
	document.getElementById("display-skills").innerText = skills.length
		? skills.join(", ")
		: "No skills added yet";

	renderList("display-education", Array.isArray(user.education) ? user.education : [], "No education added yet");
	renderList("display-projects", Array.isArray(user.projects) ? user.projects : [], "No projects added yet");
}

function showPortfolioError(message) {
	document.getElementById("display-name").innerText = message;
	document.querySelector("#display-email span").innerText = "-";
	document.querySelector("#display-phone span").innerText = "-";
	document.getElementById("display-skills").innerText = "-";
	renderList("display-education", [], "-");
	renderList("display-projects", [], "-");
}

async function loadPortfolio() {
	try {
		const response = await fetch("php/session-user.php", {
			credentials: "same-origin"
		});

		if (!response.ok) {
			window.location.href = "login.html";
			return;
		}

		const user = await response.json();
		console.log("Logged in user:", user);
		displayPortfolio(user);
	} catch (error) {
		console.error("Failed to load portfolio data:", error);
		showPortfolioError("Unable to load portfolio");
	}
}

window.addEventListener("load", loadPortfolio);