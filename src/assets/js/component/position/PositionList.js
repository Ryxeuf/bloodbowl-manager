import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {AutocompleteInput, ChipField, ReferenceArrayField, ReferenceField, ReferenceInput, SingleFieldList, TextField} from "react-admin";

const PositionFilters = [
    <ReferenceInput name={'faction'} source={'faction'} reference={'factions'}>
        <AutocompleteInput name={'faction'} optionText={'name'} filterToQuery={(searchText) => ({ name: searchText })} />
    </ReferenceInput>
]

export const PositionList = props => (
    <ListGuesser {...props} bulkActionButtons={false} filters={PositionFilters}>
        <ReferenceField reference="factions" source="faction" link="show">
            <TextField source="name" />
        </ReferenceField>
        <TextField source={"name"} />
        <TextField source={"quantity"} />
        <TextField source={"m"} />
        <TextField source={"f"} />
        <TextField source={"ag"} />
        <TextField source={"cp"} />
        <TextField source={"ar"} />
        <ReferenceArrayField reference="skills" source="skills" >
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
        <FieldGuesser source={"cost"} />
    </ListGuesser>
);