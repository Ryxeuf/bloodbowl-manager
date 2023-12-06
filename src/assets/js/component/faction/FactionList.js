import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {ChipField, ReferenceArrayField, SingleFieldList} from "react-admin";

export const FactionList = props => (
    <ListGuesser {...props} bulkActionButtons={false}>
        <FieldGuesser source={"name"}/>
        <ReferenceArrayField label="Positions" reference="positions" source="positions">
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
    </ListGuesser>
);