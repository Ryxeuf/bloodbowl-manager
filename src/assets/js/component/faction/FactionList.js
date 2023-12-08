import {ChipField, DatagridConfigurable, InfiniteList, ReferenceArrayField, SingleFieldList, TextField} from "react-admin";

export const FactionList = props => (
    <InfiniteList {...props} exporter={false}>
        <DatagridConfigurable
            bulkActionButtons={false}
            rowClick="show"
        >
            <TextField source={"name"}/>
            <ReferenceArrayField label="Positions" reference="positions" source="positions">
                <SingleFieldList linkType="show">
                    <ChipField source="name"/>
                </SingleFieldList>
            </ReferenceArrayField>
        </DatagridConfigurable>
    </InfiniteList>
);