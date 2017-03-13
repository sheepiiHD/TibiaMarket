/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function handleNav(id, project) {
    document.getElementById("project1").className = "hidden project trigger-info"
    document.getElementById("project2").className = "hidden project trigger-info"
    document.getElementById("project3").className = "hidden project trigger-info"
    document.getElementById("project4").className = "hidden project trigger-info"
    document.getElementById("project5").className = "hidden project trigger-info"
    document.getElementById("aboutme").className = "hidden trigger-info"
    document.getElementById("resume").className = "hidden trigger-info"
    document.getElementById("conclusion").className = "hidden trigger-info"
    
    /** Handle ID **/
    if(project){
       document.getElementById(id).className = "nothidden project trigger-info"
    }else{
        document.getElementById(id).className = "nothidden trigger-info"
    }
}