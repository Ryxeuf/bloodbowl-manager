import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {BooleanField, ChipField, ReferenceArrayField, SingleFieldList, TextField} from "react-admin";

export const FactionShow = props => (
    <ShowGuesser {...props}>
        <TextField source={"name"} />
        <TextField source={"quantityRerolls"} />
        <TextField source={"tier"} />
        <BooleanField source={"apothecary"} />
        <ReferenceArrayField reference="teamSpecialRules" source="specialRules" >
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
        <ReferenceArrayField reference="positions" source="positions" >
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
    </ShowGuesser>
);