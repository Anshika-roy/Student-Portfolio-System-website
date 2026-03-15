// ---------------------
// SAMPLE USER DATA
// ---------------------

let user = {
name: "Anshika Roy",
email: "anshika@email.com",
phone: "9876543210",
education: ["B.Tech Computer Science"],
skills: ["HTML","CSS","JavaScript"],
projects: ["Online Student Portfolio & Registration System"]
};


// ---------------------
// JSON CONVERSION
// ---------------------

let jsonData = JSON.stringify(user);
console.log("JSON Data:", jsonData);

let obj = JSON.parse(jsonData);
console.log("Converted Back:", obj);


// ---------------------
// DISPLAY PORTFOLIO
// ---------------------

function displayPortfolio(){

document.getElementById("display-name").innerText = user.name;

document.querySelector("#display-email span").innerText = user.email;

document.querySelector("#display-phone span").innerText = user.phone;

document.getElementById("display-skills").innerText =
user.skills.join(", ");

// Education list
let eduList = document.getElementById("display-education");
eduList.innerHTML = "";

user.education.forEach(function(edu){
let li = document.createElement("li");
li.innerText = edu;
eduList.appendChild(li);
});


// Projects list
let projectList = document.getElementById("display-projects");
projectList.innerHTML = "";

user.projects.forEach(function(project){
let li = document.createElement("li");
li.innerText = project;
projectList.appendChild(li);
});

}


// ---------------------
// AUTO LOAD DATA
// ---------------------

window.onload = displayPortfolio;