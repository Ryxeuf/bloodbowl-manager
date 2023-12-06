import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {
    BooleanField,
    ChipField,
    Datagrid,
    InfiniteList, NumberField,
    ReferenceArrayField,
    ResourceContextProvider,
    SingleFieldList,
    TextField,
    useRecordContext
} from "react-admin";
import {PositionShowPanel} from "../position/PositionShow";

export const FactionShow = props => {
    return <ShowGuesser {...props}>
        <TextField source={"name"}/>
        <TextField source={"quantityRerolls"}/>
        <TextField source={"tier"}/>
        <BooleanField source={"apothecary"}/>
        <ReferenceArrayField reference="teamSpecialRules" source="specialRules">
            <SingleFieldList linkType={false}>
                <ChipField source="name"/>
            </SingleFieldList>
        </ReferenceArrayField>
        <FactionPositionsList/>
    </ShowGuesser>
};

const FactionPositionsList = props => {
    const record = useRecordContext();

    return (
        <ResourceContextProvider value="positions">
            <InfiniteList
                hasCreate={false}
                storeKey={false}
                resource="positions"
                filter={{ faction: [record.id] }}
                title=" "
                exporter={false}
            >
                <Datagrid rowClick="show" bulkActionButtons={false}>
                    <TextField source={"name"}/>
                    <TextField source={"quantity"}/>
                    <TextField source={"m"}/>
                    <TextField source={"f"}/>
                    <TextField source={"ag"}/>
                    <TextField source={"cp"}/>
                    <TextField source={"ar"}/>
                    <NumberField source={"cost"}/>
                    <ReferenceArrayField label="Skills" reference="skills" source="skills">
                        <SingleFieldList linkType="show">
                            <ChipField source="name"/>
                        </SingleFieldList>
                    </ReferenceArrayField>
                    <FieldGuesser source={"primarySkills"}/>
                    <FieldGuesser source={"secondarySkills"}/>
                </Datagrid>
            </InfiniteList>
        </ResourceContextProvider>
    );
}