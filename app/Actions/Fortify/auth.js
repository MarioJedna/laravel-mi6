export const logout = async () => {
    try {
        const res = await axios.post("/logout");

        return res.data;
    } catch (err) {
        console.log(err.message);
    }
};
