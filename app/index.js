import React from "react";
import { ThemeProvider, CSSReset } from "@chakra-ui/core";
import customTheme from "./theme"

// const ThemedApp = () => <ThemeProvider theme={customTheme}>{props.children}</ThemeProvider>;
// // const ThemedApp = () => <ThemeProvider> <App /> </ThemeProvider>;
// ReactDOM.render(<ThemedApp />, document.getElementById('root'));

function App({ children }) {
    return (
        <ThemeProvider theme={customTheme}>
            <CSSReset />
            {children}
        </ThemeProvider>
    );
}

