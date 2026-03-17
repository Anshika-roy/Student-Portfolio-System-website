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

function renderProjects(projects) {
	const projectList = document.getElementById("display-projects");
	projectList.innerHTML = "";

	if (!projects.length) {
		const li = document.createElement("li");
		li.innerText = "No projects added yet";
		projectList.appendChild(li);
		return;
	}

	projects.forEach(function (project) {
		const li = document.createElement("li");
		const title = (project && project.title) ? project.title : "Untitled project";
		const link = (project && project.link) ? project.link : "";

		if (link) {
			const anchor = document.createElement("a");
			anchor.href = link;
			anchor.target = "_blank";
			anchor.rel = "noopener noreferrer";
			anchor.innerText = title;
			li.appendChild(anchor);
		} else {
			li.innerText = title;
		}

		projectList.appendChild(li);
	});
}

function renderCertificates(certificates) {
	const certificateList = document.getElementById("display-certificates");
	certificateList.innerHTML = "";

	if (!certificates.length) {
		const li = document.createElement("li");
		li.innerText = "No certificates added yet";
		certificateList.appendChild(li);
		return;
	}

	certificates.forEach(function (certificate) {
		const li = document.createElement("li");
		li.className = "certificate-item";

		const title = document.createElement("p");
		title.className = "certificate-title";
		title.innerText = (certificate && certificate.name) ? certificate.name : "Certificate";
		li.appendChild(title);

		const imageUrl = (certificate && certificate.image) ? certificate.image : "";
		if (imageUrl) {
			const img = document.createElement("img");
			img.src = imageUrl;
			img.alt = title.innerText + " image";
			img.className = "certificate-image";
			li.appendChild(img);
		}

		certificateList.appendChild(li);
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
	renderProjects(Array.isArray(user.projects) ? user.projects : []);
	renderCertificates(Array.isArray(user.certificates) ? user.certificates : []);
}

function showPortfolioError(message) {
	document.getElementById("display-name").innerText = message;
	document.querySelector("#display-email span").innerText = "-";
	document.querySelector("#display-phone span").innerText = "-";
	document.getElementById("display-skills").innerText = "-";
	renderList("display-education", [], "-");
	renderProjects([]);
	renderCertificates([]);
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