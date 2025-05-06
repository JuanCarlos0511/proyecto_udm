// Disbursement History Controller
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const disbursementHistoryTable = document.getElementById('disbursementHistory');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const closeBannerBtn = document.querySelector('.notification-banner .close-btn');
    const searchInput = document.querySelector('.search-box input');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    // Store disbursements data
    let disbursements = [];
    let currentFilter = 'all';
    let currentPage = 1;
    const itemsPerPage = 10;
    
    // Initialize
    fetchDisbursements();
    
    // Event listeners
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update filter and render
            currentFilter = this.dataset.filter;
            renderDisbursements(filterDisbursements(disbursements, currentFilter));
        });
    });
    
    if (closeBannerBtn) {
        closeBannerBtn.addEventListener('click', function() {
            document.querySelector('.notification-banner').style.display = 'none';
        });
    }
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredData = filterDisbursements(disbursements, currentFilter).filter(item => {
            return item.id.toLowerCase().includes(searchTerm) || 
                   item.period.toLowerCase().includes(searchTerm) || 
                   item.type.toLowerCase().includes(searchTerm);
        });
        renderDisbursements(filteredData);
    });
    
    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            renderDisbursements(filterDisbursements(disbursements, currentFilter));
        }
    });
    
    nextBtn.addEventListener('click', function() {
        const filteredData = filterDisbursements(disbursements, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            renderDisbursements(filteredData);
        }
    });
    
    // Fetch disbursements data
    function fetchDisbursements() {
        // For demo purposes, we'll use mock data
        // In a real application, this would be an API call
        
        const mockData = [
            {
                id: 'SQHR-14522',
                period: 'Dec 2023',
                type: 'Annual Bonus',
                schedule: 'Dec 31, 2023',
                status: 'Completed',
                amount: '$1,354,436'
            },
            {
                id: 'SQHR-14421',
                period: 'Dec 2023',
                type: 'Salary Dec',
                schedule: 'Dec 27, 2023',
                status: 'Awaiting',
                amount: '$3,000,436'
            },
            {
                id: 'SQHR-54720',
                period: 'Nov 2023',
                type: 'Salary Nov',
                schedule: 'Nov 27, 2023',
                status: 'Awaiting',
                amount: '$1,354,436'
            },
            {
                id: 'SQHR-15419',
                period: 'Oct 2023',
                type: 'Salary Oct',
                schedule: 'Oct 30, 2023',
                status: 'Overdue',
                amount: '$354,436'
            },
            {
                id: 'SQHR-13218',
                period: 'Sep 2023',
                type: 'Salary Sep',
                schedule: 'Sep 27, 2023',
                status: 'Completed',
                amount: '$7,354,436'
            },
            {
                id: 'SQHR-13217',
                period: 'Aug 2023',
                type: 'Salary Aug',
                schedule: 'Aug 27, 2023',
                status: 'Completed',
                amount: '$7,354,436'
            },
            {
                id: 'SQHR-13216',
                period: 'Jul 2023',
                type: 'Salary Jul',
                schedule: 'Jul 27, 2023',
                status: 'Completed',
                amount: '$7,354,436'
            },
            {
                id: 'SQHR-13215',
                period: 'Jun 2023',
                type: 'Salary Jun',
                schedule: 'Jun 27, 2023',
                status: 'Completed',
                amount: '$7,354,436'
            },
            {
                id: 'SQHR-13214',
                period: 'May 2023',
                type: 'Q1 Bonus',
                schedule: 'May 30, 2023',
                status: 'Overdue',
                amount: '$64,436'
            },
            {
                id: 'SQHR-13213',
                period: 'May 2023',
                type: 'Salary May',
                schedule: 'May 27, 2023',
                status: 'Completed',
                amount: '$7,354,436'
            }
        ];
        
        disbursements = mockData;
        renderDisbursements(filterDisbursements(disbursements, currentFilter));
        updatePagination();
    }
    
    // Filter disbursements by status
    function filterDisbursements(data, filter) {
        if (filter === 'all') {
            return data;
        }
        
        return data.filter(item => item.status.toLowerCase() === filter);
    }
    
    // Render disbursements to table
    function renderDisbursements(data) {
        disbursementHistoryTable.innerHTML = '';
        
        if (data.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="7" class="empty-message">
                <i class="far fa-file-alt"></i>
                No disbursements found
            </td>`;
            disbursementHistoryTable.appendChild(emptyRow);
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = data.slice(startIndex, endIndex);
        
        paginatedData.forEach(item => {
            const row = document.createElement('tr');
            
            // Get status class
            const statusClass = getStatusClass(item.status);
            
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.period}</td>
                <td>${item.type}</td>
                <td>${item.schedule}</td>
                <td><span class="${statusClass}">${item.status}</span></td>
                <td>${item.amount}</td>
                <td><button class="view-details-btn">View details</button></td>
            `;
            
            disbursementHistoryTable.appendChild(row);
        });
    }
    
    // Update pagination display
    function updatePagination() {
        const filteredData = filterDisbursements(disbursements, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        document.querySelector('.pagination span').textContent = `Page ${currentPage} of ${totalPages}`;
        
        // Update button states
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }
    
    // Get status class based on status text
    function getStatusClass(status) {
        status = status.toLowerCase();
        if (status === 'completed') {
            return 'status-completed';
        } else if (status === 'awaiting') {
            return 'status-awaiting';
        } else if (status === 'overdue') {
            return 'status-overdue';
        }
        return ''; // Default
    }
});
