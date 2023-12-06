import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ChipField, ReferenceArrayField, SingleFieldList} from "react-admin";

export const FactionShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"name"} />
        <ReferenceArrayField reference="positions" source="positions" >
            <SingleFieldList linkType="show">
                <ChipField source="name" />
            </SingleFieldList>
        </ReferenceArrayField>
    </ShowGuesser>
);