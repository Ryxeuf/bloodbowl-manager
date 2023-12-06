import {EditGuesser} from "@api-platform/admin";
import {ChipField, ReferenceArrayField, SingleFieldList, TextInput} from "react-admin";

export const FactionEdit = props => (
    <EditGuesser {...props}>
        <TextInput name={'name'} source={"name"}/>
        <ReferenceArrayField label="positions" reference="positions" source="positions">
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
    </EditGuesser>
);