// assets/js/user_search.js

function searchUsers() {
    let query = document.getElementById('userSearchInput').value;
    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', '../api/search_users.php?q=' + encodeURIComponent(query), true);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let users = JSON.parse(xhr.responseText);
            let tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            
            users.forEach(user => {
                let statusText = user.is_active == 1 ? 'Active' : 'Inactive';
                let statusColor = user.is_active == 1 ? 'var(--success)' : 'var(--danger)';
                
                let row = `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td><span class="badge badge-${user.role}">${user.role.toUpperCase()}</span></td>
                        <td><span style="color: ${statusColor}">${statusText}</span></td>
                        <td>
                            <a href="../controllers/user_controller.php?action=toggle_status&id=${user.id}" class="btn ${user.is_active == 1 ? 'btn-delete' : 'btn-approve'}">
                                ${user.is_active == 1 ? 'Deactivate' : 'Activate'}
                            </a>
                            <a href="../controllers/user_controller.php?action=delete&id=${user.id}" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }
    };
    
    xhr.send();
}
