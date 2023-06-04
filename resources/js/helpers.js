export const sortParamFromSorter = (sorter) => {
    if (sorter.column === undefined) {
        return undefined;
    }

    return {
        key: sorter.columnKey,
        desc: sorter.order == 'descend',
    };
}
