const { useState, useEffect } = React;

function GameGrid() {
  const [grid, setGrid] = useState([]);
  const [generation, setGeneration] = useState(0);

  const numRows = 25;
  const numCols = 25;

  const generateEmptyGrid = () =>
    Array.from({ length: numRows }, () =>
      Array.from({ length: numCols }, () => Math.random() > 0.8 ? 1 : 0)
    );

  useEffect(() => {
    setGrid(generateEmptyGrid());
  }, []);

  useEffect(() => {
    const interval = setInterval(() => {
      setGrid(prev => {
        const next = prev.map((row, i) =>
          row.map((cell, j) => {
            const neighbors = [
              [-1, -1], [-1, 0], [-1, 1],
              [0, -1],         [0, 1],
              [1, -1], [1, 0], [1, 1],
            ];
            let liveNeighbors = 0;

            neighbors.forEach(([x, y]) => {
              const newI = (i + x + numRows) % numRows;
              const newJ = (j + y + numCols) % numCols;
              liveNeighbors += prev[newI][newJ];
            });

            if (cell === 1 && (liveNeighbors < 2 || liveNeighbors > 3)) return 0;
            if (cell === 0 && liveNeighbors === 3) return 1;
            return cell;
          })
        );
        setGeneration(g => g + 1);
        return next;
      });
    }, 1000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div>
      <p>React Grid - Generation {generation}</p>
      <div style={{
        display: "grid",
        gridTemplateColumns: `repeat(${numCols}, 20px)`,
        margin: "auto"
      }}>
        {grid.flat().map((cell, i) => (
          <div
            key={i}
            style={{
              width: 20,
              height: 20,
              backgroundColor: cell ? "blue" : "white",
              border: "solid 1px #ccc"
            }}
          />
        ))}
      </div>
    </div>
  );
}

const root = ReactDOM.createRoot(document.getElementById("react-grid"));
root.render(<GameGrid />);
