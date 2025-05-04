let grid = [];
let gameInterval;
let generation = 0;
let population = 0;

const gridContainer = document.getElementById('grid-container');
const generationDisplay = document.getElementById('generation');
const populationDisplay = document.getElementById('population');

function createGrid() {
    gridContainer.innerHTML = ''; // Clear the grid
    grid = [];
    for (let row = 0; row < 50; row++) {
        grid[row] = [];
        for (let col = 0; col < 50; col++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');
            cell.dataset.row = row;
            cell.dataset.col = col;
            gridContainer.appendChild(cell);
            grid[row][col] = false; // Initialize as dead cells

            // Add click event to toggle cell state
            cell.addEventListener('click', toggleCellState);
        }
    }
}

function toggleCellState(e) {
    const cell = e.target;
    const row = cell.dataset.row;
    const col = cell.dataset.col;
    grid[row][col] = !grid[row][col];
    cell.style.backgroundColor = grid[row][col] ? 'black' : '#f0f0f0';
}

function startGame() {
    if (gameInterval) return; // Prevent multiple intervals
    gameInterval = setInterval(runGameLoop, 100); // 100ms for game update
}

function stopGame() {
    clearInterval(gameInterval);
    gameInterval = null;
}

function nextGen() {
    runGameLoop();
}

function resetGame() {
    clearInterval(gameInterval);
    gameInterval = null;
    generation = 0;
    population = 0;
    generationDisplay.textContent = generation;
    populationDisplay.textContent = population;
    createGrid(); // Recreate the grid
}

function runGameLoop() {
    const newGrid = grid.map(arr => arr.slice()); // Clone grid for next generation

    for (let row = 0; row < 50; row++) {
        for (let col = 0; col < 50; col++) {
            const neighbors = countNeighbors(row, col);
            if (grid[row][col]) {
                if (neighbors < 2 || neighbors > 3) {
                    newGrid[row][col] = false; // Cell dies
                }
            } else {
                if (neighbors === 3) {
                    newGrid[row][col] = true; // Cell comes to life
                }
            }
        }
    }

    grid = newGrid; // Update grid
    updateGridDisplay(); // Refresh grid display

    generation++;
    generationDisplay.textContent = generation;
}

function countNeighbors(row, col) {
    const directions = [
        [-1, -1], [-1, 0], [-1, 1], // top-left, top, top-right
        [0, -1], [0, 1],             // left, right
        [1, -1], [1, 0], [1, 1]      // bottom-left, bottom, bottom-right
    ];

    let count = 0;
    directions.forEach(([dr, dc]) => {
        const r = row + dr;
        const c = col + dc;
        if (r >= 0 && r < 50 && c >= 0 && c < 50 && grid[r][c]) {
            count++;
        }
    });
    return count;
}

function updateGridDisplay() {
    for (let row = 0; row < 50; row++) {
        for (let col = 0; col < 50; col++) {
            const cell = gridContainer.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            cell.style.backgroundColor = grid[row][col] ? 'black' : '#f0f0f0';
        }
    }
}

function loadPattern(pattern) {
    const patternData = patterns[pattern];
    if (patternData) {
        resetGame();
        patternData.forEach(([row, col]) => {
            grid[row][col] = true;
            const cell = gridContainer.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            cell.style.backgroundColor = 'black';
        });
    }
}

createGrid(); // Initialize grid when page loads
