const formatters = () => {
    const formatSeconds = seconds => {
        if (isNaN(seconds)) seconds = 0;
        return new Date(seconds * 1000).toISOString().substr(11, 8);
    }

    return { formatSeconds }
};

export default formatters;
