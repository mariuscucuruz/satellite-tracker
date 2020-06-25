import {
    Stack,
    Flex,
    Box,
    Grid,
    Progress,
    Button
} from "@chakra-ui/core";

const App = () => {
    const flexSettings = {
        flex: "1",
        minW: "300px",
        textAlign: "center",
        color: "white",
        mx: "6",
        mb: "6"
    };

    const gridSettings = {
        w: "100%",
        textAlign: "center",
        color: "white",
    };

    return (
    <div>
        <Flex w="100%" justify="space-between" flexWrap="wrap">
            <Box {...flexSettings} bg={"red.500"}>I'm a box</Box>
            <Box {...flexSettings} bg={"blue.500"}>I'm a box</Box>
            <Box {...flexSettings} bg={"green.500"}>I'm a box</Box>
            <Box {...flexSettings} bg={"purple.500"}>I'm a box</Box>
        </Flex>

        <Grid w="100%" templateColumns="repeat(auto-fit, minmax(300px, 1fr))" gap={6}>
            <Box {...gridSettings} bg={"red.500"}>I'm a box</Box>
            <Box {...gridSettings} bg={"blue.500"}>I'm a box</Box>
            <Box {...gridSettings} bg={"green.500"}>I'm a box</Box>
            <Box {...gridSettings} bg={"purple.500"}>I'm a box</Box>
        </Grid>
    </div>
);
};
